<?php

Route::group(['namespace' => 'Admin'], function () {
    // Dashboard
    Route::get('/', 'HomeController@index')->name('admin.home');

    // Login
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('admin.logout');

    // Register
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('admin.register');
    Route::post('register', 'Auth\RegisterController@register');

    // Reset Password
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('admin.password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('admin.password.update');

    // Confirm Password
    Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('admin.password.confirm');
    Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

    // Verify Email
    Route::get('email/verify', 'Auth\VerificationController@show')->name('admin.verification.notice');
    Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('admin.verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend')->name('admin.verification.resend');
});


Route::namespace('Admin')->group(function () {

    Route::prefix('/view-users')->group(function () {
        Route::get('/farm-managers', 'AdminController@fetch_managers')->name('view-managers');
        Route::get('/commodity-distributors', 'AdminController@fetch_distributors')->name('view-distributors');
        Route::get('/commodity-retailers', 'AdminController@fetch_retailers')->name('view-retailers');
        Route::get('/commodity-consumers', 'AdminController@fetch_consumers')->name('view-consumers');
        Route::get('/logistics-companies', 'AdminController@fetch_logistics')->name('view-logistics');
    });

    Route::prefix('/view-orders')->group(function () {
        Route::get('/distributors', 'AdminController@fetch_orders')->name('distributors.orders');
        Route::get('/retailers', 'AdminController@fetch_orders')->name('retailers.orders');
        Route::get('/consumers', 'AdminController@fetch_orders')->name('consumers.orders');
    });

    Route::get('/view-requests/logistics', 'AdminController@fetch_logistic_requests')->name('logistics.requests');

    Route::get('/view-transactions', 'AdminController@fetch_transactions')->name('view-transactions');

    Route::get('/new-user', 'UserController@create')->name('new-user');
    Route::post('/new-user', 'UserController@store')->name('admin.add-user');
});