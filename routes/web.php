<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//Auth::routes();


Route::auth();

Route::get('refresh-csrf', function(){return csrf_token();});

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('/restaurants', 'RestaurantsController@index');
Route::get('/login', 'Auth\LoginController@index');
Route::get('/register', 'Auth\RegisterController@index');

Route::get('storage/{filename}', function ($filename)
{
    $path = storage_path('public/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

//Route::get('storage/{filename}', function ($filename)
//{
//    return Image::make(storage_path('public/' . $filename))->response();
//});

// misc pages
Route::get('/site/{page}', ['as' => 'getPage', 'uses' => 'PageController@getPage']);



// requires user to be logged in
Route::group(['middleware' => 'web'], function() {
    Route::get('/order', 'OrderController@index');
    Route::get('/account', 'AccountController@index');
    Route::get('/manage', 'ManageController@index');

//    Route::get('/manage/filter/{statusList?}/', ['uses' => 'manageController@index'], function() {
////        return Route::input();
//    });

    Route::get('/manage/filter/', 'manageController@index');
    Route::get('/schedule', 'ScheduleController@index');
});

// requires driver permissions
Route::group(['middleware' => ['web', 'driver']], function() {
    Route::get('/driver', 'DriverController@index');
});


// requires admin permissions
Route::group(['middleware' => ['web', 'admin']], function() {
    Route::get('/admin', 'Admin\SettingsController@index');
    Route::get('/admin/settings', 'Admin\SettingsController@index');
    Route::get('/admin/orders', 'Admin\OrdersController@index');
    Route::get('/admin/transactions', 'Admin\TransactionsController@index');
    Route::get('/admin/payroll', 'Admin\PayrollController@index');
    Route::get('/admin/schedule', 'ScheduleController@adminView');
    Route::get('/admin/schedule/configure', 'ScheduleController@configureView');
    Route::get('/admin/drivers', 'Admin\DriversController@index');
    Route::get('/admin/users', 'Admin\UsersController@index');
    Route::get('/admin/users/{id}', 'Admin\UsersController@profile');
});



/*
    ROUTE POSTS ==================================================================================
    ========================================================================================
*/

// user posts
Route::post('user/login', 'Auth\LoginController@login');
Route::post('user/logout', 'Auth\LoginController@logout');
Route::post('user/register', 'Auth\RegisterController@register');
Route::post('user/updateInfo', 'AccountController@updateInfo');
Route::post('user/changeEmail', 'AccountController@changeEmail');
Route::post('user/changePhone', 'AccountController@changePhone');
Route::post('user/changePassword', 'AccountController@changePassword');
Route::post('user/removePaymentMethod', 'AccountController@removePaymentMethod');


// order posts
Route::post('order/create', 'OrderController@create');
Route::post('order/cancel', 'OrderController@cancel');
Route::post('order/stepBack', 'OrderController@stepBack');
Route::post('order/submitDetails', 'OrderController@submitDetails');
Route::post('order/submitAddress', 'OrderController@submitAddress');
Route::post('order/applyPromo', 'OrderController@applyOrderPromo');
Route::post('order/validateCreditCard', 'OrderController@validateCreditCard');
Route::post('order/authCreditCard', 'OrderController@authCreditCard');
Route::post('order/deleteCreditCard', 'OrderController@deleteCreditCard');
Route::post('order/submitOrderFeedback', 'OrderController@submitOrderFeedback');


// driver posts
Route::post('driver/selfAssignOrder', 'DriverController@selfAssignOrder');
Route::post('driver/updateOrderCost', 'DriverController@updateOrderCost');
Route::post('driver/sendArrivalStatus', 'DriverController@sendArrivalStatus');
Route::post('driver/markComplete', 'DriverController@markComplete');


// manage posts
Route::post('manage/deleteOrder', 'ManageController@deleteOrder');
Route::post('manage/editOrderDetails', 'ManageController@editOrderDetails');
Route::post('manage/updateOrderStatus', 'ManageController@updateOrderStatus');
Route::post('manage/collectPayment', 'ManageController@collectPayment');
Route::post('manage/assignDriver', 'ManageController@assignDriver');
Route::post('manage/unassignDriver', 'ManageController@unassignDriver');
Route::post('manage/updateOrderInfo', 'ManageController@updateOrderInfo');
Route::post('manage/updateOrderCosts', 'ManageController@updateOrderCosts');
Route::post('manage/updateOrderStatus', 'ManageController@updateOrderStatus');


// schedule posts
Route::post('schedule/takeShift', 'ScheduleController@takeShift');
Route::post('schedule/reqUnshift', 'ScheduleController@reqUnshift');
Route::post('schedule/createShift', 'ScheduleController@createShift');
Route::post('schedule/deleteShift', 'ScheduleController@deleteShift');


//admin posts
Route::post('admin/updateSettings', 'Admin\SettingsController@updateSettings');
Route::post('admin/getOrderStats', 'Admin\OrdersController@getOrdersInRange');
Route::post('admin/getTransactionStats', 'Admin\TransactionsController@getTransactionsInRange');
Route::post('admin/removeShift', 'ScheduleController@removeShift');
Route::post('admin/userSearch', 'Admin\SettingsController@userSearch');
Route::post('admin/createTestOrder', 'Admin\OrdersController@createTestOrder');

