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

Route::get('/', function () {
    return view('index');
});

Route::get('api/jobs', 'JobController@getJobList');
Route::get('api/jobs/{id}', 'JobController@getJobStatus');
Route::get('api/jobs/queue', 'JobController@getQueuingJobs');
Route::get('api/jobs/{id}/log', 'JobController@showJobLog');

Route::group(['middleware' => 'apiauth'], function () {
    Route::post('api/jobs', 'JobController@createJob');
    Route::post('api/jobs/{id}', 'JobController@assignJob');
    Route::post('api/jobs/{id}/log', 'JobController@syncJobLog');
    Route::get('api/jobs/{id}/judge', 'JobController@judge');
});
