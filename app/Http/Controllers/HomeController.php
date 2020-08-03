<?php

namespace App\Http\Controllers;

use App\User;
use DataTables;
use App\Commodity;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;

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
        return view('home');
    }


    public function getUsers() {
        $user = User::query();
        return DataTables::of($user)
        // ->setRowClass(function ($user) {
        //     return $user->id % 2 == 0 ? 'alert-success' : 'alert-danger';
        // })
        ->setRowClass('{{ $id % 2 == 0 ? "alert-success" : "alert-danger"}}')
        ->setRowId(function($user) {
            return $user->uuid;
        })
        ->setRowAttr([
            'row_id' => function($user) {
            return $user->name;
        }])
        ->setRowAttr([
            'align' => 'center'
        ])
        ->setRowData([
            'data-name' => 'row-{{$id}}'
        ])
        ->addColumn('intro', 'Hi {{$name}}')
        ->editColumn('created_at', function($user) {
            return $user->created_at->diffForHumans();
        })
        ->addColumn('action', function($user) {
            return '<button id="view_user" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> View</button>';
        })
        ->rawColumns(['action'])
        // ->addColumn('role', function(User $user) {
        //     return $user->role->name;
        // })

        ->editColumn('name', function(User $user) {
            return strtoupper($user->name);
        })
        // ->removeColumn('updated_at')
        ->make(true);
    }


    public function profile(UserDataTable $dataTable) {
        return $dataTable->render('pages.user.profile');
        // return view('pages.user/profile');
    }


    public function getCommodities() {
        $commodities = Commodity::pluck('name');
        return response()->json($commodities);
    }
}
