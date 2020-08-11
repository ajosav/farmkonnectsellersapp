<?php

namespace App\Http\Controllers\Order;

use App\Model\Unit;
use App\Model\Order;
use App\Model\Product;
use Illuminate\Http\Request;
use App\Http\Requests\CalculatePriceRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use GuzzleHttp\Exception\ClientException;
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

        $place_order = DB::transaction(function () use ($wallet, $total_price, $order) {

            $debit_wallet = $wallet->debit_wallet($total_price, Auth::user()->uuid);

            if ($debit_wallet == false) {
                # code...

                throw new ModelNotFoundException("Error Processing Request");
            }

            if ($order->save() == false) {

                throw new ModelNotFoundException("Error Processing Request");
            }


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
}