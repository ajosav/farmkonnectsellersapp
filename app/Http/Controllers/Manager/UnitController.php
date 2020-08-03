<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Unit;

class UnitController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'verified']);
    }

    public function index() {
        $units = Unit::where('base_unit', null)->select('unit_name', 'id')->get();
        return response()->json($units);
    }
    public function saleUnit($id) {
        $unit = Unit::where("base_unit", $id)->orWhere('id', $id)->select('unit_name', 'id')->get();
        return response()->json($unit);
    }
}
