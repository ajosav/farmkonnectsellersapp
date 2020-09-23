<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Model\Order;
use App\Model\Wallet;
use App\Model\Delivery;
use App\Model\Transaction;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
        $this->middleware('admin.verified');
    }

    /**
     * Show the Admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $total_wallet_balance = Wallet::all()->sum('balance');
        $count_transactions = Transaction::all()->count();
        $count_orders = Order::all()->count();
        $count_users = User::all()->count();
        $count_completed_deliveries = Delivery::Where('status', 6)->count();

        return view('admin.home', ['count_users' => $count_users, 'count_transactions' => $count_transactions, 'count_orders' => $count_orders, 'total_wallet_balance' => $total_wallet_balance, 'count_completed_deliveries' => $count_completed_deliveries]);
    }
}