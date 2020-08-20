<?php

use App\User;
use App\UserPosition;
use App\Model\Product;
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

    return redirect()->route('login');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/getUsers', 'HomeController@getUsers')->name('get.users');

Route::get('/all_commodities', 'HomeController@getCommodities')->name('get.commodities');

Route::get('/bank-account', 'Wallet\WalletController@accounts');
Route::post('bank-account', 'Wallet\WalletController@bank_account')->name('bank-account');

Route::get('/transactions', 'Wallet\WalletController@show');


Route::namespace('Wallet')->prefix('/wallet')->group(function () {
    Route::get('/', 'WalletController@index')->name('wallet');

    Route::get('/confirm-deposit', array('as' =>  'transactions.status', 'uses' => 'WalletController@status'));

    Route::get('/withdraw', 'WalletController@create')->middleware('password.confirm')->name('withdraw');
    Route::post('/withdraw', 'WalletController@confirm_withdrawal');

    Route::post('/cookie', 'WalletController@set_cookie')->name('cookie');

    Route::post('/withdraw-money', 'WalletController@withdraw_money');
});


Route::namespace('Profile')->group(function () {
    Route::get('/getProfile', 'ProfileController@userProfile');
    Route::resource('/profile', 'ProfileController');
});


Route::namespace('Order')->prefix('/orders')->group(function () {

    Route::get('/', 'OrderController@index')->name('orders');
    Route::get('/make_order', 'OrderController@getProducts')->name('get.allProducts');

    Route::post('/cal_price', 'OrderController@calculatePrice')->name('get-price');
    Route::post('/process-order', 'OrderController@store')->name('process-order');

    Route::get('/order-requests', 'OrderController@requests')->name('requests');

    Route::post('/cancel-order', 'OrderController@cancel')->name('order.cancel');
});

Route::namespace('Manager')->group(function () {
    Route::get('/product/fetch_units', 'UnitController@index');
    Route::get('/product/sale_unit/{id}', 'UnitController@saleUnit');
    Route::resource('/product', 'ProductController');
});

Route::namespace('CommodityRetailer')->prefix('retailer')->group(function () {
});