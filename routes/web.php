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
# CronJob Public
Route::get('/cron-jobs/run',  'Api\\CronJobController@run');

Route::get('/', function () {
    //return view('site.index');
    return view('site.index');
});
