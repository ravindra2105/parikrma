<?php

use Illuminate\Http\Request;

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
Use App\Rest;

//Route::post('register', 'UserController@register');

Route::post('login', 'UserController@authenticate');

Route::any('/common/getPoleTypes', 'CommonController@getPoleTypes');
Route::any('/common/getDivisions', 'CommonController@getDivisions');
Route::any('/common/getLocations', 'CommonController@getLocations');
Route::any('/common/getFeeders', 'CommonController@getFeeders');

Route::any('/common/getDivisionBySiteId', 'CommonController@getDivisionBySiteId');
Route::any('/common/getLocationByDivisionId', 'CommonController@getLocationByDivisionId');
Route::any('/common/getFeederByLocationId', 'CommonController@getFeederByLocationId');

Route::any('/common/getDataByPole', 'CommonController@getDataByPole');



Route::any('/users/uploaddata', 'UserController@uploaddata');     

Route::group(['middleware' => ['jwt.verify']], function() {
    
    Route::post('/users/addScreenShot','UserController@addScreenShot');
        
    Route::any('mark_attendance', 'AttendanceController@mark_attendance');   
    Route::post('apilogout', 'UserController@apilogout');     

    Route::post('changePassword', 'UserController@changePassword');    
    Route::post('editMyProfile', 'UserController@editMyProfile');    
    Route::post('dashboard', 'Controller@dashboard');    
     
    
    Route::post('/ajaxNotificationData', 'Controller@ajaxNotificationData');
    
    Route::post('getAllSiteInfo', 'SiteController@getAllSiteInfo');     
    
    Route::post('/sites/getSiteInfoBySiteId', 'SiteController@getSiteInfoBySiteId');
    Route::post('/sites/getSiteInfoByUser', 'SiteController@getSiteInfoByUser');
    Route::post('/sites/getSiteImages', 'SiteController@getSiteImages');
    
    
    Route::post('/users/getAttendance', 'UserController@getAttendance');

    Route::post('/users/getManPower', 'UserController@getManPower');
    Route::post('/users/markManpowerAttendance', 'UserController@markManpowerAttendance');
    Route::post('/users/getManPowerAttendance', 'UserController@getManPowerAttendance');
    
    Route::post('/users/addinfo', 'UserController@addinfo');

    

    
    
    
});

