<?php

namespace App\Http\Controllers\CommodityRetailer;

use View;
use App\User;
use Exception;
use App\Commodity;
use Keygen\Keygen;
use App\Model\Unit;
use App\Model\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Session;


class MakeOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
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

    public function calculatePrice(Request $request)
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