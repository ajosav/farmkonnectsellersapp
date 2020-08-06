<?php

namespace App\Http\Controllers\CommodityRetailer;

use App\Http\Controllers\Controller;
use App\Model\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   $msg = ""; 
        $user_id = auth()->user()->uuid;
        $product_id = $request->product_id;
        $quantity_ordered = $request->quantity_ordered;
        $totalprice = $request->totalprice;
        // $data = [
        //     'user_id'=>$user_id,
        //     'product_id'=>$product_id,
        //     'quantity_ordered'=>$quantity_ordered,
        //     'total_price'=>$totalprice,
        // ];
        // $create_order = Order::create($data);
        $create_order = new Order;
        $create_order->user_id = $user_id;
        $create_order->product_id = $product_id;
        $create_order->quantity_ordered = $quantity_ordered;
        $create_order->total_price = $totalprice;
        $create_order->save();
        if($create_order){
            return $msg = "order_successful";
        }
        //dd($data);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}