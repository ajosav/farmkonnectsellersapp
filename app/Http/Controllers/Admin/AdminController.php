<?php

namespace App\Http\Controllers\Admin;

use App\Model\Wallet;
use App\Model\Transaction;
use App\FarmManagerProfile;
use Illuminate\Http\Request;
use App\LogisticCompanyProfile;
use App\CommodityConsumerProfile;
use App\CommodityRetailerProfile;
use App\CommodityDistributorProfile;
use App\Http\Controllers\Controller;

class AdminController extends Controller
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
    {
        //
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

    public function fetch_managers()
    {
        $managers = FarmManagerProfile::all();
        $count = 0;
        return view('admin.view-managers', ['managers' => $managers, 'count' => $count]);
    }

    public function fetch_distributors()
    {
        $distributors = CommodityDistributorProfile::all();
        $count = 0;

        return view('admin.view-distributors', ['distributors' => $distributors, 'count' => $count]);
    }

    public function fetch_retailers()
    {
        $retailers = CommodityRetailerProfile::all();
        $count = 0;

        return view('admin.view-retailers', ['retailers' => $retailers, 'count' => $count]);
    }

    public function fetch_consumers()
    {
        $consumers = CommodityConsumerProfile::all();
        $count = 0;

        return view('admin.view-consumers', ['consumers' => $consumers, 'count' => $count]);
    }

    public function fetch_logistics()
    {
        $logistics = LogisticCompanyProfile::all();
        $count = 0;

        return view('admin.view-logistics', ['logistics' => $logistics, 'count' => $count]);
    }

    public function fetch_transactions()
    {
        $transactions = Transaction::paginate(50);

        $total_credit = Transaction::where('type', 'Credit')->where('status', 1)->sum('amount');

        $total_debit = Transaction::where('type', 'Debit')->where('status', 1)->sum('amount');
        $count = 0;

        return view('admin.transactions', ['transactions' => $transactions, 'count' => $count, 'total_credit' => $total_credit, 'total_debit' => $total_debit]);
    }
}