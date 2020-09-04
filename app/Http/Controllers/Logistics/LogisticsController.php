<?php

namespace App\Http\Controllers\Logistics;

use App\Model\Order;
use GuzzleHttp\Client;
use App\Model\Delivery;
use Illuminate\Http\Request;
use App\LogisticCompanyProfile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use GuzzleHttp\Exception\RequestException;
use App\Events\DeliverySuccessfullyRequested;
use App\Http\Controllers\Wallet\WalletController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LogisticsController extends Controller
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


    // Using Haversine formula to calculate the shortest distance between both coordinates.

    protected function get_distance($point1, $point2)
    {
        // array of lat-long i.e  $point1 = [lat,long]
        $earth_radius = 6371;  // earth radius in km
        $point1_lat = $point1["latitude"];
        $point2_lat = $point2["latitude"];
        $delta_lat = deg2rad($point2_lat - $point1_lat);
        $point1_long = $point1["longitude"];
        $point2_long = $point2["longitude"];
        $delta_long = deg2rad($point2_long - $point1_long);
        $a = sin($delta_lat / 2) * sin($delta_lat / 2) + cos(deg2rad($point1_lat)) * cos(deg2rad($point2_lat)) * sin($delta_long / 2) * sin($delta_long / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earth_radius * $c;
        $distance = round($distance, 2);

        return $distance;    // in km
    }

    // Fetch the latitude and longitude of input address
    protected function get_coordinates($address)
    {

        try {
            $client = new Client();
            $res = $client->request('GET', 'https://maps.googleapis.com/maps/api/geocode/json', [
                'query' =>  [
                    'address' => urlencode($address),
                    'key' => env('GOOGLE_MAPS_API_KEY')
                ]
            ]);

            $response = json_decode($res->getBody());
            $latitude = $response->results[0]->geometry->location->lat;
            $longitude = $response->results[0]->geometry->location->lng;
            $coordinates = [
                "status" => 200,
                "latitude" => $latitude,
                "longitude" => $longitude
            ];

            return $coordinates;
        } catch (RequestException $ex) {
            $response = [
                'status' => 400,
                'message' => "Error Fetching Coordinates"
            ];

            return $response;
        }
    }


    public function calculate_distance(Request $request)
    {
        if ($request->ajax()) {
            # code...
            $delivery = $request->delivery;
            $pickup = $request->pickup;

            $delivery_coordinates = $this->get_coordinates($delivery);

            $pickup_coordinates = $this->get_coordinates($pickup);

            if ($pickup_coordinates['status']  != 200 || $delivery_coordinates['status'] != 200) {
                # code...
                return response()->json(['status' => 0, 'msg' => 'Error Fetching Coordinates.']);
            }

            $distance = $this->get_distance($pickup_coordinates, $delivery_coordinates);

            $response = [
                'status' => 1,
                'distance' => $distance
            ];

            return response()->json($response);;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
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

        $order = Order::where('uuid', $request->order_id)->where('status', 1)->firstOrFail();

        $logistics = LogisticCompanyProfile::all();

        return view('pages.marketplace.request-delivery', ['order' => $order, 'role' => $role, 'logistics' => $logistics]);
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
        $delivery_request = $request->delivery_request;

        $order_id = $delivery_request['order_id'];

        $rate = $delivery_request['rate'];
        $company_id = $delivery_request['logistic_company'];
        $distance = $delivery_request['distance'];
        $fee = $delivery_request['fee'];
        $destination = $delivery_request['destination'];
        $pickup = $delivery_request['pickup'];
        $date = $delivery_request['date'];
        $details = $delivery_request['details'];

        $delivery = [
            'order_id' => $order_id,
            'logistic_id' => $company_id,
            'pickup_point' => $pickup,
            'destination' => $destination,
            'distance' => $distance,
            'status' => 2,
            'rate' => $rate,
            'fee' => $fee,
            'date' => $date,
            'details' => $details
        ];

        $wallet = new WalletController();

        $make_request = DB::transaction(function () use ($wallet, $fee, $delivery) {

            $debit_wallet = $wallet->debit_wallet($fee, Auth::user()->uuid);

            if ($debit_wallet == false) {
                # code...

                throw new ModelNotFoundException("Error Processing Request");
            }

            $save_request = Delivery::create((array) $delivery);

            if ($save_request == false) {

                throw new ModelNotFoundException("Error Processing Request");
            }

            event(new DeliverySuccessfullyRequested((object) $delivery));

            return true;
        });



        if ($make_request == false) {
            # code...

            $response = [
                'status' => 0,
                'msg' => 'Error requesting for delivery. Please try again later.'
            ];

            return response()->json($response);
        }

        $response = [
            'status' => 1,
            'msg' => 'Delivery Successfully Requested.'
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
}