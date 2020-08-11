<?php

namespace App\Http\Controllers\Profile;

use App\User;
use Exception;
use App\Model\Product;
use App\FarmManagerProfile;
use Illuminate\Http\Request;
use App\LogisticCompanyProfile;
use App\CommodityConsumerProfile;
use App\CommodityRetailerProfile;
use App\CommodityDistributorProfile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use App\Http\Requests\ProfileValidation;
use App\Http\Resources\ProfileResource\ManagerProfileResource;
use App\Http\Resources\ProfileResource\ConsumerProfileResource;
use App\Http\Resources\ProfileResource\LogisticProfileResource;
use App\Http\Resources\ProfileResource\RetailerProfileResource;
use App\Http\Resources\ProfileResource\SupplierProfileResource;
use Prophecy\Exception\Doubler\MethodNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\ProfileResource\ConsumerProfileResource;
use App\Http\Resources\ProfileResource\LogisticProfileResource;
use App\Http\Resources\ProfileResource\RetailerProfileResource;
use App\Http\Resources\ProfileResource\SupplierProfileResource;

class ProfileController extends Controller
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
        $user = auth()->user();
        if(Gate::allows('Farm Manager')) {
            $status = $user->farmManagerProfile != null ? 'true' : 'false';
        } elseif(Gate::allows('Commodity Distributor')) {
            $status = $user->commodityDistributorProfile != null ? 'true' : 'false';
        } elseif(Gate::allows('Commodity Retailer')) {
            $status = $user->commodityRetailerProfile != null ? 'true' : 'false';
        } elseif(Gate::allows('Commodity Consumer')) {
            $status = $user->commodityConsumerProfile != null ? 'true' : 'false';
        } elseif(Gate::allows('Logistic Company')) {
            $status = $user->logisticCompanyProfile != null ? 'true' : 'false';
        } else {
            return redirect('/home')->with('denied', 'You are not authorized');
        }
        return view('pages.user.profile', compact('status'));
    }

    public function userProfile() {
        $user = auth()->user();
        if(request()->wantsJson()) {
            if(Gate::allows('Farm Manager')) {
                if($user->farmManagerProfile != null) {
                    return new ManagerProfileResource($user->farmManagerProfile);
                }
            } elseif(Gate::allows('Commodity Distributor')) {
                if($user->commodityDistributorProfile != null) {
                    return new SupplierProfileResource($user->commodityDistributorProfile);
                }
            } elseif(Gate::allows('Commodity Retailer')) {
                if($user->commodityRetailerProfile != null) {
                    return new RetailerProfileResource($user->commodityRetailerProfile);
                }
            } elseif(Gate::allows('Commodity Consumer')) {
                if($user->commodityConsumerProfile != null) {
                    return new ConsumerProfileResource($user->commodityConsumerProfile);
                }
            } elseif(Gate::allows('Logistic Company')) {
                if($user->logisticCompanyProfile != null) {
                    return new LogisticProfileResource($user->logisticCompanyProfile);
                }
            }
            return abort(433, 'You are not authorized');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return Product::addSelect(User::whereColumn('uuid', 'products.uuid')->where('users.position', 2))->get();
        dd(User::permission('Farm Manager')->select('uuid')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileValidation $request)
    {
        if(Gate::allows('Farm Manager')){
            FarmManagerProfile::createProfile($request);
            return response()->json('Success, User profile sucessfully created');
        } elseif(Gate::allows('Commodity Distributor')) {
            CommodityDistributorProfile::createProfile($request);
            return response()->json('Success, User profile sucessfully created');
        } elseif(Gate::allows('Commodity Retailer')) {
            CommodityRetailerProfile::createProfile($request);
            return response()->json('Success, User profile sucessfully created');
        } elseif(Gate::allows('Commodity Consumer')) {
            CommodityConsumerProfile::createProfile($request);
            return response()->json('Success, User profile sucessfully created');
        } elseif(Gate::allows('Logistic Company')) {
            LogisticCompanyProfile::createProfile($request);
            return response()->json('Success, User profile sucessfully created');
        }
        return abort(433, 'You are not authorized');

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
    public function update(ProfileValidation $request, $id)
    {
        
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
}
