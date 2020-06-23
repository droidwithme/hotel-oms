<?php

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

// For index route
Route::get('/', ['as' => 'index', 'uses' => 'IndexController@index']);

/* For the admin protected routes */
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin'], 'namespace' => 'Web\Admin\ProtectedPages'], function () {

    Route::get('/dashboard', ['as'=>'admin.dashboard.index', 'uses'=> 'DashboardController@index']);

    // Hotels
    Route::get('store', ['as' => 'admin.hotel.index', 'uses' => 'HotelController@index']); // show all items
    Route::post('store/password/show', ['as' => 'admin.hotel.password.view', 'uses' => 'HotelController@showHotelPassword']); // show all items
    Route::get('store/create', ['as' => 'admin.hotel.create', 'uses' => 'HotelController@create']); // show the add new items
    Route::post('store', ['as' => 'admin.hotel.store', 'uses' => 'HotelController@store']); // store new items
    Route::get('store/edit/{id}', ['as' => 'admin.hotel.edit', 'uses' => 'HotelController@edit']); // edit existing items
    Route::patch('store/update/{id}', ['as' => 'admin.hotel.update', 'uses' => 'HotelController@update']); // update existing items
    Route::delete('store/delete/{id}', ['as' => 'admin.hotel.delete', 'uses' => 'HotelController@destroy']); // delete existing items
    Route::get('store/deleted/show', ['as' => 'admin.hotel.deleted.show', 'uses' => 'HotelController@showDeleted']); // delete existing items
    Route::put('store/deleted/restore/{id}', ['as' => 'admin.hotel.deleted.restore', 'uses' => 'HotelController@restoreDeleted']); // delete existing items

    // Hotels Menu Items
    Route::get('store/{hotelId}/product', ['as' => 'admin.hotel.menu-item.index', 'uses' => 'HotelMenuItemController@index']); // show all items
    Route::get('store/{hotelId}/product/create', ['as' => 'admin.hotel.menu-item.create', 'uses' => 'HotelMenuItemController@create']); // show the add new items
    Route::post('store/{hotelId}/product', ['as' => 'admin.hotel.menu-item.store', 'uses' => 'HotelMenuItemController@store']); // store new items
    Route::get('store/{hotelId}/product/edit/{id}', ['as' => 'admin.hotel.menu-item.edit', 'uses' => 'HotelMenuItemController@edit']); // edit existing items
    Route::patch('store/{hotelId}/product/update/{id}', ['as' => 'admin.hotel.menu-item.update', 'uses' => 'HotelMenuItemController@update']); // update existing items
    Route::get('store/{hotelId}/product/duplicate/{id}', ['as' => 'admin.hotel.menu-item.duplicate', 'uses' => 'HotelMenuItemController@duplicate']); // edit existing items
    Route::post('store/{hotelId}/product/duplicate/store', ['as' => 'admin.hotel.menu-item.duplicate.store', 'uses' => 'HotelMenuItemController@storeDuplicate']); // update existing items
    Route::delete('store/{hotelId}/product/delete/{id}', ['as' => 'admin.hotel.menu-item.delete', 'uses' => 'HotelMenuItemController@destroy']); // delete existing items
    Route::get('store/{hotelId}/product/deleted/show', ['as' => 'admin.hotel.menu-item.deleted.show', 'uses' => 'HotelMenuItemController@showDeleted']); // delete existing items
    Route::put('store/{hotelId}/product/deleted/restore/{id}', ['as' => 'admin.hotel.menu-item.deleted.restore', 'uses' => 'HotelMenuItemController@restoreDeleted']); // delete existing items

    // Hotel Category
    Route::get('store-category', ['as' => 'admin.hotel-category.index', 'uses' => 'HotelCategoryController@index']); // show all items
    Route::get('store-category/create', ['as' => 'admin.hotel-category.create', 'uses' => 'HotelCategoryController@create']); // show the add new items
    Route::post('store-category', ['as' => 'admin.hotel-category.store', 'uses' => 'HotelCategoryController@store']); // store new items
    Route::get('store-category/edit/{id}', ['as' => 'admin.hotel-category.edit', 'uses' => 'HotelCategoryController@edit']); // edit existing items
    Route::patch('store-category/update/{id}', ['as' => 'admin.hotel-category.update', 'uses' => 'HotelCategoryController@update']); // update existing items
    Route::delete('store-category/delete/{id}', ['as' => 'admin.hotel-category.delete', 'uses' => 'HotelCategoryController@destroy']); // delete existing items
    Route::get('store-category/deleted/show', ['as' => 'admin.hotel-category.deleted.show', 'uses' => 'HotelCategoryController@showDeleted']); // delete existing items
    Route::put('store-category/deleted/restore/{id}', ['as' => 'admin.hotel-category.deleted.restore', 'uses' => 'HotelCategoryController@restoreDeleted']); // delete existing items

    // Menu Item Category
    Route::get('product-category', ['as' => 'admin.menu-item-category.index', 'uses' => 'MenuItemCategoryController@index']); // show all items
    Route::get('product-category/create', ['as' => 'admin.menu-item-category.create', 'uses' => 'MenuItemCategoryController@create']); // show the add new items
    Route::post('product-category', ['as' => 'admin.menu-item-category.store', 'uses' => 'MenuItemCategoryController@store']); // store new items
    Route::get('product-category/edit/{id}', ['as' => 'admin.menu-item-category.edit', 'uses' => 'MenuItemCategoryController@edit']); // edit existing items
    Route::patch('product-category/update/{id}', ['as' => 'admin.menu-item-category.update', 'uses' => 'MenuItemCategoryController@update']); // update existing items
    Route::delete('product-category/delete/{id}', ['as' => 'admin.menu-item-category.delete', 'uses' => 'MenuItemCategoryController@destroy']); // delete existing items
    Route::get('product-category/deleted/show', ['as' => 'admin.menu-item-category.deleted.show', 'uses' => 'MenuItemCategoryController@showDeleted']); // delete existing items
    Route::put('product-category/deleted/restore/{id}', ['as' => 'admin.menu-item-category.deleted.restore', 'uses' => 'MenuItemCategoryController@restoreDeleted']); // delete existing items

    // Advertisement
    Route::get('advertisement', ['as' => 'admin.advertisement.index', 'uses' => 'AdvertisementController@index']); // show all items
    Route::get('advertisement/create', ['as' => 'admin.advertisement.create', 'uses' => 'AdvertisementController@create']); // show the add new items
    Route::post('advertisement', ['as' => 'admin.advertisement.store', 'uses' => 'AdvertisementController@store']); // store new items
    Route::get('advertisement/edit/{id}', ['as' => 'admin.advertisement.edit', 'uses' => 'AdvertisementController@edit']); // edit existing items
    Route::patch('advertisement/update/{id}', ['as' => 'admin.advertisement.update', 'uses' => 'AdvertisementController@update']); // update existing items
    Route::delete('advertisement/delete/{id}', ['as' => 'admin.advertisement.delete', 'uses' => 'AdvertisementController@destroy']); // delete existing items
    Route::get('advertisement/deleted/show', ['as' => 'admin.advertisement.deleted.show', 'uses' => 'AdvertisementController@showDeleted']); // delete existing items
    Route::put('advertisement/deleted/restore/{id}', ['as' => 'admin.advertisement.deleted.restore', 'uses' => 'AdvertisementController@restoreDeleted']); // delete existing items


    // Orders
    Route::get('order/new', ['as' => 'admin.order.new', 'uses' => 'OrderController@receivedOrders']); // show all orders
    Route::get('order/proceeded', ['as' => 'admin.order.proceeded', 'uses' => 'OrderController@proceededOrders']); // show all orders
    Route::get('order/completed', ['as' => 'admin.order.completed', 'uses' => 'OrderController@completedOrders']); // show all orders
    Route::get('order/all', ['as' => 'admin.order.all', 'uses' => 'OrderController@allOrders']); // show all orders
    Route::get('order/{orderId}/detail', ['as' => 'admin.order.detail', 'uses' => 'OrderController@orderDetail']); // show all orders
    Route::post('order/order-status-update', ['as' => 'admin.order.status.update', 'uses' => 'OrderController@updateOrderStatus']); // show all orders

    // App Users
    Route::get('app-user', ['as' => 'admin.app-user.index', 'uses' => 'ApiUserController@index']); // show all items
    Route::post('app-user/password/show', ['as' => 'admin.app-user.password.view', 'uses' => 'ApiUserController@showAppUserPassword']); // show all items
    Route::delete('app-user/delete/{id}', ['as' => 'admin.app-user.delete', 'uses' => 'ApiUserController@destroy']); // delete existing items
});

Route::group(['prefix' => 'store', 'middleware' => ['auth:hotel'], 'namespace' => 'Web\Hotel\ProtectedPages'], function () {

    // Orders
    Route::get('order/proceeded', ['as' => 'hotel.order.proceeded', 'uses' => 'OrderController@proceededOrders']); // show all orders
    Route::get('order/completed', ['as' => 'hotel.order.completed', 'uses' => 'OrderController@completedOrders']); // show all orders
    Route::get('order/all', ['as' => 'hotel.order.all', 'uses' => 'OrderController@allOrders']); // show all orders
    Route::get('order/{orderId}/detail', ['as' => 'hotel.order.detail', 'uses' => 'OrderController@orderDetail']); // show all orders

    // Edit Profile
    Route::get('profile/edit', ['as' => 'hotel.profile.edit', 'uses' => 'ProfileController@edit']); // edit existing items
    Route::patch('profile/update', ['as' => 'hotel.profile.update', 'uses' => 'ProfileController@update']); // update existing items

});

/* For the admin authentication routes */
Route::group(['prefix' => 'admin/auth', 'namespace' => 'Web\Admin\Auth'], function () {
    //Authentication Routes
    Route::get('login', ['as'=>'admin.auth.login', 'uses'=> 'LoginController@showLoginForm']);
    Route::post('login', ['as'=>'admin.auth.doLogin', 'uses'=> 'LoginController@login']);
    Route::get('logout', ['as'=>'admin.auth.logout', 'uses'=> 'LoginController@logout']);
});

/* For the vendor authentication routes */
Auth::routes();

Route::get('/store/logout', function () {
    Auth::logout();
    return redirect()->route('index');
})->name('hotel.auth.logout');

// For Forgot Password Routes
Route::group(['prefix' => 'store/auth', 'namespace' => 'PasswordResets'], function () {
    Route::get('/forgot-password', ['as' => 'hotel.auth.forgot-password', 'uses' => 'HotelPasswordResetController@showPasswordResetForm']);
    Route::post('/forgot-password', ['as' => 'hotel.auth.forgot-password.send-mail', 'uses' => 'HotelPasswordResetController@sendPasswordResetMail']);
    Route::get('/forgot-password/token/{token}', ['as' => 'hotel.auth.forgot-password.reset-password.form', 'uses' => 'HotelPasswordResetController@resetPasswordForm']);
    Route::post('/forgot-password/token/{token}', ['as' => 'hotel.auth.forgot-password.reset-password.update', 'uses' => 'HotelPasswordResetController@resetPasswordUpdate']);
});