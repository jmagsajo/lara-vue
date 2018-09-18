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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::get('getToken', 'api\ApiController@getToken');

Route::group([
  'middleware' => 'auth:api'
], function() {
    Route::get('users', 'api\UserController@index')->name('users');
    Route::post('user/create', 'api\UserController@store')->name('users.create');
    Route::get('user/delete/{id}', 'api\UserController@destroy')->name('users.delete');
    Route::post('user/update/{id}', 'api\UserController@update')->name('users.update');
    Route::get('user/listByPage/{page}', 'api\UserController@listByPage')->name('users.listByPage');
});
