<?php

namespace App\Http\Controllers\Manager;

use App\DataTables\ProductDatatable;
use Exception;
use Keygen\Keygen;
use App\Model\Unit;
use App\Model\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
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
    public function index(ProductDatatable $dataTable)
    {
        if(!(Gate::allows('Farm Manager'))) {
            return redirect()->back()->with(['denied' => "Access Denied!"]);
        }

        return $dataTable->render('pages.manager.product.products');
        // return view('pages.manager.product.products');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!(Gate::allows('Farm Manager'))) {
            return redirect()->back()->with(['denied' => "Access Denied!"]);
        }
        return view('pages.manager.product.create_products');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $data = $request->except('image', 'finishDate', 'startDate');
        // if($request->category != '' || !empty($request->category)) {
        //     $data['category'] = implode(',', $data['category']);
        // }
        $data['created_by'] = auth()->user()->uuid;

        $data['start_date'] = $request->startDate;
        $data['end_date'] = $request->finishDate;

        $prefix = strtoupper('PRO-'.Str::substr(Str::of($data['name'])->before(' '), 0, 3));
        $keygen = Keygen::length(10)->mutable('length', 'prefix');
        $product_code = $keygen->numeric()->prefix($prefix, false)->generate();
        $data['code'] = $product_code;
        $images = $request->image;
        $image_names = [];
        if($images) {
            foreach ($images as $key => $image) {
                $imageName = $image->getClientOriginalName();
                $newImage = \Image::make($image->getRealPath());
                Storage::put('public/products/large/'.$imageName, $newImage->stream());
                $newImage->resize(120, 120, function($constraint) {
                    $constraint->aspectRatio();
                });
                $newImage->stream();
                Storage::put('public/products/small/'.$imageName, $newImage, 'public');
                $image_names[] = $imageName;
            }
            $data['image'] = implode(",", $image_names);
        }

        Product::create($data);
        Session::flash('success', 'Product created successfully');

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

    public function findProductByUUid($uuid) {
        return Product::findByUuid($uuid);
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
    public function destroy($uuid)
    {
        try{
            $product = $this->findProductByUUid($uuid);
            $product->delete();
            return redirect()->back()->with('success', __('Product successfully deleted'));
        } catch(Exception $e) {
            Session()->flash('error', __('Product could not be deleted, contact the admin'));
        }
    }


}
