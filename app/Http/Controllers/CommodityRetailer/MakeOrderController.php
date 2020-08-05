<?php

namespace App\Http\Controllers\CommodityRetailer;
use App\User;
use App\Commodity;
use View;
use Exception;
use Keygen\Keygen;
use App\Model\Unit;
use App\Model\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Session;


class MakeOrderController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'verified']);
    }
    public function getProducts() {
        if(Gate::denies('Commodity Retailer')) {
            return redirect('/home')->with('denied', 'Permission Denied');
        }
        $products = Product::paginate(15);

        
        return view("pages.cr.make_order", ["products"=>$products]);
        //return response()->json($products);
        //return View::make('commodityretailer.make_order')->with('products', $products);
    }
    public function calculatePrice(Request $request){
        $msg = '';
    	$product = Product::findByUuid($request->uuid);
    	$unit = Unit::where('id', $request->unit_id)->firstOrFail();
    	$operator = $unit->operator;
    	$operational_value = $unit->operation_value;
    	$base_unit = $unit->base_unit;
    	//return $base_unit;
    	if($base_unit){
    		$get_base_unit = Unit::where('id', $base_unit)->firstOrFail();
    		$base_unit_operation_value = $get_base_unit->operation_value;
    	}else{
    		$base_unit_operation_value = 1;
    	}
    	$price = (int) $product->price;
    	$quantity = $request->quantity;
        $quantity_in_stock = $product->quantity;
    	$response = $product->calcProductPrice($base_unit_operation_value, $operator, $operational_value, $price, $quantity);
        $price_of_product = $response['price_of_product'];
        $total_quantity =$response['total_quantity'];
        if($quantity_in_stock < $total_quantity){
            $msg = "quantity_less";
        }
        $details = ['price_of_product'=>$price_of_product, 'total_quantity'=>$total_quantity, 'msg'=>$msg];
        return $details;
        //$response['price_of_product'];
    	//return($response);
    	//return $request->all();
    }
}
