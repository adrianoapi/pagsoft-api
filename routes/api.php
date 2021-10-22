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


    Route::get ('collections/',                'Api\\CollectionController@index');
    Route::get ('collections/collection/{id}', 'Api\\CollectionController@getCollection' );
    Route::post('collections/',                'Api\\CollectionController@create');
    Route::put ('collections/{id}',            'Api\\CollectionController@edit');

    Route::delete('collections/{id}', 'Api\\CollectionController@destroy');


    # password
    Route::get ('password/',                'Api\\PasswordController@index');
    Route::get ('password/{id}',            'Api\\PasswordController@findById');
    Route::post('password/',                'Api\\PasswordController@create');
    Route::put ('password/{id}',            'Api\\PasswordController@edit');

    Route::delete('password/{id}', 'Api\\PasswordController@destroy');

    # collectionItem
    Route::get ('collectionItem/{id}',      'Api\\CollectionItemController@findById');
    Route::post('collectionItem/',          'Api\\CollectionItemController@create');
    Route::put ('collectionItem/{id}',      'Api\\CollectionItemController@edit');
    Route::delete('collectionItem/{id}',    'Api\\CollectionItemController@destroy');

    # Dashboard
    Route::get ('dashbaord/finance',                'Api\\DashboardController@finance');

});

