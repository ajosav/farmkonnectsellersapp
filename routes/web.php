<?php

use App\User;
use App\UserPosition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    if(auth()->check()) {
        $user = auth()->user();
        $user->syncPermissions([$user->positionName->name]);
        return redirect()->route('login');
    }
    return redirect()->route('login');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/getUsers', 'HomeController@getUsers')->name('get.users');

Route::get('/all_commodities', 'HomeController@getCommodities')->name('get.commodities');


Route::namespace('Profile')->group(function() {
    Route::get('/getProfile', 'ProfileController@userProfile');
    Route::resource('/profile', 'ProfileController');
});

Route::namespace('Manager')->group(function() {
    Route::get('/product/fetch_units', 'UnitController@index');
    Route::get('/product/sale_unit/{id}', 'UnitController@saleUnit');
    Route::resource('/product', 'ProductController');
});




// Route::get('/profile', 'HomeController@profile')->name('get.profile');
