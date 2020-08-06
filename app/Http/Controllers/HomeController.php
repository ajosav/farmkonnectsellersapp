<?php

namespace App\Http\Controllers;

use App\User;
use DataTables;
use App\Commodity;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $wallet = Auth::user()->wallet;

        if ($wallet == null) {

            event(new Verified(Auth::user()));
        }

        return view('home');
    }

    public function profile(UserDataTable $dataTable)
    {
        return $dataTable->render('pages.user.profile');
        // return view('pages.user/profile');
    }


    public function getCommodities()
    {
        $commodities = Commodity::pluck('name');
        return response()->json($commodities);
    }
}