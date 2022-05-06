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

    # password
    Route::get ('ledgerEntries/',                'Api\\LedgerEntryController@index' );
    Route::get ('ledgerEntries/flow',            'Api\\LedgerEntryController@flow' );
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

    #Collection
    Route::get ('collections/',                'Api\\CollectionController@index');
    Route::get ('collections/collection/{id}', 'Api\\CollectionController@getCollection' );
    Route::get ('collections/{id}',            'Api\\CollectionController@findById' );
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

    # TransitionType
    Route::get('transitionType/list', 'Api\\TransitionTypeController@list');

    # LedgerGroupController
    Route::get('ledgerGroup/list', 'Api\\LedgerGroupController@list');

    # FixedCost
    Route::get ('fixedCost/',             'Api\\FixedCostController@index');
    Route::get ('fixedCost/',             'Api\\FixedCostController@index');
    Route::get ('fixedCost/{id}',         'Api\\FixedCostController@findById');
    Route::post('fixedCost/',             'Api\\FixedCostController@create');
    Route::put ('fixedCost/{id}',         'Api\\FixedCostController@edit');
    Route::put ('fixedCost/{id}/trash',   'Api\\FixedCostController@trash');
    Route::put ('fixedCost/{id}/restore', 'Api\\FixedCostController@restore');

    Route::delete('fixedCost/{id}',     'Api\\FixedCostController@destroy');

    # TaskGroupController
    Route::get('taskGroup', 'Api\\TaskGroupController@index');

    # TaskController
    Route::get ('task',             'Api\\TaskController@index');
    Route::post('task',             'Api\\TaskController@create');
    Route::get ('task/{id}',        'Api\\TaskController@findById');
    Route::put ('task/{id}/update', 'Api\\TaskController@update');

    Route::delete('task/delete', 'Api\\TaskController@destroy');

    # TaskController
    Route::get ('event',             'Api\\EventController@index');
    Route::post('event',             'Api\\EventController@create');
    Route::put ('event/{id}', 'Api\\EventController@edit');

    Route::delete('event/{id}', 'Api\\EventController@destroy');


});
