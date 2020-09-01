<?php

namespace App\Http\Controllers\Order;

use App\Model\Unit;
use App\Model\Order;
use App\Model\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\OrderDeclined;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event;
use App\Events\OrderSuccessfullyPlaced;
use GuzzleHttp\Exception\ClientException;
use App\Http\Requests\CalculatePriceRequest;
use App\Http\Controllers\Wallet\WalletController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $orders = Order::where('user_id', Auth::user()->uuid)->with('product')->latest()->get();

        $role = false;

        if (Gate::allows('Commodity Distributor')) {

            $role = "farmManagerProfile";
        }

        if (Gate::allows('Commodity Retailer')) {

            $role = "commodityDistributorProfile";
        }

        if (Gate::allows('Commodity Consumer')) {

            $role = "commodityRetailerProfile";
        }

        if ($role == false) {
            # code...
            return redirect()->back()->with('error', 'Access Denied.');
        }

        return view('pages.marketplace.orders', ['orders' => $orders, 'role' => $role]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function validate_order($product_id, $quantity_ordered)
    {
        $product = Product::findByUuid($product_id);
        $quantity_in_stock = $product->quantity;

        if ($quantity_in_stock < $quantity_ordered) {
            # code...

            return false;
        }

        return true;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $user_id = auth()->user()->uuid;
        $product_id = $request->product_id;
        $quantity_ordered = $request->quantity_ordered;
        $unit_id = $request->unit_id;

        $valid_order = $this->validate_order($product_id, $quantity_ordered);

        if ($valid_order == false) {
            # code...

            $response = [
                'status' => '0',
                'msg' => 'Kindly input a valid quantity.'
            ];

            return response()->json($response);
        }

        $wallet_balance = Auth::user()->wallet->balance;

        $total_price = $request->totalprice;

        if ($wallet_balance < $total_price) {

            # code...
            $response = [
                'status' => '0',
                'msg' => 'Insufficient Wallet Balance.'
            ];

            return response()->json($response);
        }

        $wallet = new WalletController();

        $order = new Order();
        $order->user_id = $user_id;
        $order->product_id = $product_id;
        $order->quantity_ordered = $quantity_ordered;
        $order->total_price = $total_price;
        $order->unit_id = $unit_id;

        $place_order = DB::transaction(function () use ($wallet, $total_price, $order) {

            $debit_wallet = $wallet->debit_wallet($total_price, Auth::user()->uuid);

            if ($debit_wallet == false) {
                # code...

                throw new ModelNotFoundException("Error Processing Request");
            }

            if ($order->save() == false) {

                throw new ModelNotFoundException("Error Processing Request");
            }

            event(new OrderSuccessfullyPlaced($order));

            return true;
        });

        if ($place_order) {
            # code...
            $response = [
                'status' => '1',
                'msg' => 'Your order has been successfully placed.'
            ];

            return response()->json($response);
        }

        $response = [
            'status' => '0',
            'msg' => 'Error Placing Order. Please try again later.'
        ];

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getProducts()
    {

        if (Gate::allows('Commodity Distributor')) {

            $profile = Auth::user()->commodityDistributorProfile;

            if ($profile == null) {
                # code...

                return redirect('/profile')->with('error', 'Please update your profile to continue');
            }

            $products = Product::whereHas('owner', function ($query) {
                return $query->permission('Farm Manager');
            })->paginate(10);

            $role = "farmManagerProfile";

            return view("pages.marketplace.products", ["products" => $products, "role" => $role]);
        }

        if (Gate::allows('Commodity Retailer')) {

            $profile = Auth::user()->commodityRetailerProfile;

            if ($profile == null) {
                # code...

                return redirect('/profile')->with('error', 'Please update your profile to continue');
            }

            $products = Product::whereHas('owner', function ($query) {
                return $query->permission('Commodity Distributor');
            })->paginate(10);

            $role = "commodityDistributorProfile";

            return view("pages.marketplace.products", ["products" => $products, 'role' => $role]);
        }

        if (Gate::allows('Commodity Consumer')) {

            $profile = Auth::user()->commodityConsumerProfile;

            if ($profile == null) {
                # code...

                return redirect('/profile')->with('error', 'Please update your profile to continue');
            }

            $products = Product::whereHas('owner', function ($query) {
                return $query->permission('Commodity Retailer');
            })->paginate(10);

            $role = "commodityRetailerProfile";

            return view("pages.marketplace.products", ["products" => $products, 'role' => $role]);
        }


        return redirect('/home')->with('denied', 'Permission Denied');
    }

    public function calculatePrice(CalculatePriceRequest $request)
    {
        $msg = '';

        $product = Product::findByUuid($request->uuid);

        $unit = Unit::where('id', $request->unit_id)->firstOrFail();

        $operator = $unit->operator;

        $operational_value = $unit->operation_value;

        $base_unit = $unit->base_unit;

        if ($base_unit) {
            $get_base_unit = Unit::where('id', $base_unit)->firstOrFail();
            $base_unit_operation_value = $get_base_unit->operation_value;
        } else {
            $base_unit_operation_value = 1;
        }

        $price = (int) $product->price;
        $quantity = $request->quantity;
        $quantity_in_stock = $product->quantity;

        $response = $product->calcProductPrice($base_unit_operation_value, $operator, $operational_value, $price, $quantity);
        $price_of_product = $response['price_of_product'];
        $total_quantity = $response['total_quantity'];

        if ($quantity_in_stock < $total_quantity) {
            $msg = "quantity_less";
        }

        $details = [
            'price_of_product' => $price_of_product,
            'total_quantity' => $total_quantity,
            'msg' => $msg
        ];

        return $details;
    }

    public function requests()
    {
        $requests = Order::whereHas('product', function ($query) {
            return $query->where('created_by', Auth::user()->uuid);
        })->where('status', 2)->oldest()->get();

        return view("pages.manager.product.requests", ['requests' => $requests, 'role' => 'CommodityDistributorProfile']);
    }

    public function order_quantity($uuid)
    {
        $order = Order::where('uuid', $uuid)->first(); //fetching the order

        $product = $order->product;  // fetching the product associated with the order.

        $unit_id = $order->unit_id;  // fetching the unit id of the order

        $unit = Unit::where('id', $unit_id)->first();   // fetching the unit

        $operator = $unit->operator;  // fetching the operation for the specified unit.

        $operation_value = $unit->operation_value;    // fetching the operational_value for the unit

        if ($unit->base_unit == null) {
            # code...

            $unit->base_unit = 1;
        }

        $base_unit = Unit::where('id', $unit->base_unit)->first();

        $base_unit_operation_value = $base_unit->operation_value;

        $quantity_ordered = $product->calc_quantity($base_unit_operation_value, $operator, $operation_value, $order->quantity_ordered);

        return $quantity_ordered;
    }

    public function sell_product($uuid)
    {

        $order = Order::where('uuid', $uuid)->first();

        $sale = DB::transaction(function () use ($order) {

            $sale = false;

            $quantity_ordered = $this->order_quantity($order->uuid);

            $product = Product::where('uuid', $order->product->uuid)->first();

            //Check if buyer has purchased the product from the same vendor before.
            $existing_product = Product::where('created_by', $order->user_id)->where('sold_by', Auth::user()->uuid)->where('name', $product->name)->first();

            if ($existing_product == null) {
                # code...

                $products = $product->replicate();
                $products->created_by = $order->user_id;
                $products->sold_by = Auth::user()->uuid;
                $products->quantity = $order->quantity_ordered;
                $products->uuid = Str::uuid()->toString();

                if ($products->save() == null) {
                    # code...
                    throw new ModelNotFoundException("Error Occurred. Kindly try again later.");
                }

                $sale = true;
            }

            if ($existing_product != null) {
                # code...
                $quantity_in_stock = $existing_product->quantity;
                $update_quantity = $existing_product->update([
                    'quantity' => $quantity_in_stock + $quantity_ordered
                ]);

                if ($update_quantity == false) {
                    # code...

                    throw new ModelNotFoundException("Error Occurred. Kindly try again later.");
                }

                $sale = true;
            }

            if ($sale == false) {
                # code...
                return false;
            }

            $deduct = $product->update([
                'quantity' => $product->quantity - $quantity_ordered
            ]);

            if ($deduct == false) {
                # code...
                throw new ModelNotFoundException('Error Occurred. Kindly try again later');
            }

            return true;
        });

        if ($sale != true) {
            # code...
            return false;
        }

        return true;
    }

    public function cancel(Request $request)
    {
        if ($request->ajax()) {
            # code...
            $order_uuid = $request->order_uuid;

            $cancel_order = Order::where('uuid', $order_uuid)->where('user_id', Auth::user()->uuid)->update(['status' => 4]);

            if ($cancel_order == false) {
                # code...
                throw new ModelNotFoundException("Error Cancelling Order.");
            }

            $response = [
                'status' => '1',
                'msg' => 'Order Successfully Cancelled.'
            ];

            return response()->json($response);
        }
    }

    public function accept_requests(Request $request)
    {
        if ($request->ajax()) {
            # code...
            $uuid = $request->uuid;

            $feedback = $request->feedback;

            $order_quantity = $this->order_quantity($uuid);

            $quantity_in_stock = Order::where('uuid', $uuid)->first()->product->quantity;

            if ($quantity_in_stock < $order_quantity) {
                # code...

                $response = [
                    'status' => '0',
                    'msg' => 'Order Quantity exceeds product stock quantity.'
                ];

                return $response;
            }

            $sell_product = $this->sell_product($uuid);

            if ($sell_product == false) {
                # code...
                $response = [
                    'status' => '0',
                    'msg' => 'Error Selling Product. Kindly try again later'
                ];

                return $response;
            }

            $update_order = Order::where('uuid', $uuid)->update([
                'status' => 1,
                'feedback' => $feedback
            ]);

            $response = [
                'status' => '1',
                'msg' => 'Product sale successfully confirmed.'
            ];

            return $response;
        }
    }



    public function decline_requests(Request $request)
    {
        if ($request->ajax()) {
            # code...
            $uuid = $request->uuid;

            $request->validate([
                'feedback' => ['required']
            ]);

            $feedback = $request->feedback;


            $decline_request = Order::findByUuid($uuid)->update([
                'status' => 0,
                'feedback' => $feedback
            ]);

            if ($decline_request == false) {
                # code...
                throw new ModelNotFoundException("Error Declining Request.");
            }

            $response = [
                'status' => '1',
                'msg' => 'Request Successfully Declined.'
            ];

            $order = Order::where('uuid', $uuid)->first();

            event(new OrderDeclined($order));

            return response()->json($response);
        }
    }
}