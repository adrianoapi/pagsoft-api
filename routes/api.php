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

    Route::get ('auth/collection', 'Api\\UserController@getCollection');
    Route::post('auth/logout',     'Api\\AuthController@logout');
    Route::get ('auth/me',         'Api\\AuthController@me');
    Route::get ('users',           'Api\\UserController@index');

    Route::get ('ledgerEntries/',                'Api\\LedgerEntryController@index' );
    Route::get ('ledgerEntries/{id}',            'Api\\LedgerEntryController@findById');
    Route::get ('ledgerEntries/collection/{id}', 'Api\\LedgerEntryController@getCollection' );
    Route::post('ledgerEntries/',                'Api\\LedgerEntryController@create');
    Route::put ('ledgerEntries/{id}',            'Api\\LedgerEntryController@edit');

    Route::delete('ledgerEntries/{id}', 'Api\\LedgerEntryController@destroy');

    Route::get ('ledgerItems/',     'Api\\LedgerItemController@index');
    Route::get ('ledgerItems/{id}', 'Api\\LedgerItemController@findById');
    Route::post('ledgerItems/',     'Api\\LedgerItemController@create');
    Route::put ('ledgerItems/{id}', 'Api\\LedgerItemController@edit');

    Route::delete('ledgerItems/{id}', 'Api\\LedgerItemController@destroy');


    Route::get ('collections/',     'Api\\CollectionController@index');
    Route::delete('collections/{id}', 'Api\\CollectionController@destroy');
});

