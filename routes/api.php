<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Api v1 routes
Route::group(['prefix' => 'v1'], function () {

    //Api routes for admin
    Route::group(['prefix' => 'admin'], function () {

        // Auth Route
        Route::group(['prefix' => 'auth', 'namespace'=>'Api\Admin\Auth'], function () {
            Route::post('login', ['as' => 'api.admin.auth.login', 'uses' => 'AuthController@login']);
            Route::get('validate-token', ['as' => 'api.admin.auth.validate-token', 'uses' => 'AuthController@validateToken']);
        });

        // Orders Route
        Route::group(['prefix' => 'order', 'middleware' => 'jwt.auth', 'namespace'=>'Api\Admin\ProtectedPages'], function () {
            Route::get('list', ['as' => 'api.admin.orders', 'uses' => 'AdminOrdersController@index']);
            Route::post('update-status', ['as' => 'api.admin.order.status-update', 'uses' => 'AdminOrdersController@changeOrderStatus']);
        });
    });

    //Api routes for hotel
    Route::group(['prefix' => 'hotel'], function () {

        // Auth Route
        Route::group(['prefix' => 'auth', 'namespace'=>'Api\Hotel\Auth'], function () {
            Route::post('login', ['as' => 'api.hotel.auth.login', 'uses' => 'AuthController@login']);
            Route::get('validate-token', ['as' => 'api.hotel.auth.validate-token', 'uses' => 'AuthController@validateToken']);
        });

        // Orders Route
        Route::group(['prefix' => 'order', 'middleware' => 'jwt.auth', 'namespace'=>'Api\Hotel\ProtectedPages'], function () {
            Route::get('list', ['as' => 'api.hotel.orders', 'uses' => 'HotelOrdersController']);
        });

        // Hotel Route
        Route::group(['middleware' => 'jwt.auth', 'namespace'=>'Api\Hotel\ProtectedPages'], function () {
            Route::post('update-profile', ['as' => 'api.hotel.profile', 'uses' => 'VendorController@editVendorProfileDetail']);
            Route::post('update-profile-picture', ['as' => 'api.hotel.profile-picture', 'uses' => 'VendorController@updateVendorProfilePicture']);
            Route::post('update-location', ['as' => 'api.hotel.location', 'uses' => 'VendorController@updateVendorCoordinates']);
        });
    });

    //Api routes for user
    Route::group(['prefix' => 'user'], function () {

        // Auth Route
        Route::group(['prefix' => 'auth', 'namespace'=>'Api\User\Auth'], function () {
            Route::post('login', ['as' => 'api.user.auth.login', 'uses' => 'AuthController@login']);
            Route::post('register', ['as' => 'api.user.auth.register', 'uses' => 'AuthController@register']);
            Route::post('verify-mobile', ['as' => 'api.user.auth.verify-token', 'uses' => 'AuthController@verifyVerificationCode']);
            Route::get('logout', ['as' => 'api.user.auth.logout', 'uses' => 'AuthController@logout'])->middleware('jwt.auth');

            Route::post('/forgot-password', ['as' => 'api.user.auth.forgot-password.send-mail', 'uses' => 'PasswordResetController@sendPasswordResetMail']);
            Route::get('/forgot-password/token/{token}', ['as' => 'api.user.auth.forgot-password.reset-password.form', 'uses' => 'PasswordResetController@resetPasswordForm']);
            Route::post('/forgot-password/token/{token}', ['as' => 'api.user.auth.forgot-password.reset-password.update', 'uses' => 'PasswordResetController@resetPasswordUpdate']);
        });

        // User Route
        Route::group(['middleware' => 'jwt.auth', 'namespace'=>'Api\User\ProtectedPages'], function () {
            Route::post('update-profile', ['as' => 'api.user.profile', 'uses' => 'UserController@editUserProfileDetail']);
            Route::post('update-profile-picture', ['as' => 'api.user.profile-picture', 'uses' => 'UserController@updateUserProfilePicture']);
            Route::post('update-location', ['as' => 'api.user.location', 'uses' => 'UserController@updateUserCoordinates']);
            Route::get('order-history', ['as' => 'api.user.product.order-history', 'uses' => 'UserController@getUserOrderHistory']);
        });
    });

    //Api routes for order related stuff
    Route::group(['prefix' => 'menu', 'middleware' => 'jwt.auth', 'namespace'=>'Api\Menu'], function () {
        Route::get('hotel-category', ['as' => 'menu.hotel-category', 'uses' => 'MenuController@getHotelCategoryList']);
        Route::post('hotel-list', ['as' => 'menu.hotel-list', 'uses' => 'MenuController@getHotelList']);
        Route::post('menu-item-list', ['as' => 'menu.menu-item-list', 'uses' => 'MenuController@getMenuItemList']);
        Route::post('create-order', ['as' => 'menu.menu-item-list', 'uses' => 'MenuController@createOrder']);
    });
});
