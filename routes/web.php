<?php

use Illuminate\Support\Facades\Route;

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
    return redirect()->to('/login');
});


Route::get('/addUser', 'HomeController@add')->name('addUser');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/getStatesByCountry','Controller@getStatesByCountry');

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:cache');
    
    // return what you want
});

Route::group(['middleware' => ['auth']], function() {

    


    Route::post('/notifications/ajaxGetUserByType','NotificationController@ajaxGetUserByType');
    Route::any('/notifications/ajaxSendNotification','NotificationController@ajaxSendNotification');
    Route::any('/notifications/sendnotification','NotificationController@sendnotification');
    Route::any('/notifications/ajaxGetNotificationById','NotificationController@ajaxGetNotificationById');
    Route::any('/notifications/ajaxGetNotification','NotificationController@ajaxGetNotification');
    Route::any('/notifications/ajaxNotificationData','NotificationController@ajaxNotificationData');

    Route::any('/roles/copy/{id}', 'RoleController@copy');
    Route::resource('roles','RoleController');
    
    Route::resource('subscriptions','SubscriptionController');
    Route::any('/subscriptions/destroy', 'SubscriptionController@destroy');
    Route::any('/subscriptions/ajaxData', 'SubscriptionController@ajaxData');
    

    Route::resource('users','UserController');
    Route::any('/users/ajaxData', 'UserController@ajaxData');
    Route::any('/users/listsite', 'UserController@listsite');
    Route::any('/users/assignsiteaccess', 'UserController@assignsiteaccess');

    Route::post('payment', 'PaymentController@payment')->name('payment');

    
    
    

    Route::any('/registrations/createh','RegistrationController@createh');
    Route::resource('registrations','RegistrationController');
    Route::any('/registrations/ajaxData', 'RegistrationController@ajaxData');
    Route::any('/registrations/icard/{id}', 'RegistrationController@icard');
    

    Route::resource('notifications','NotificationController');



});
