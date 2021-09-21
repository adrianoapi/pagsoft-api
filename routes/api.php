<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('auth/login', 'Api\\AuthController@login');

Route::group(['middleware' => ['apiJwt']], function(){

    Route::get('auth/collection', 'Api\\UserController@getCollection');
    Route::post('auth/logout', 'Api\\AuthController@logout');
    Route::get('auth/me', 'Api\\AuthController@me');
    Route::get('users', 'Api\\UserController@index');

    Route::get ('ledgerEntries/',              'Api\\LedgerEntryController@index' );
    Route::post('ledgerEntries/',              'Api\\LedgerEntryController@create');
    Route::put('ledgerEntries/{ledgerEntry}', 'Api\\LedgerEntryController@edit');
});

