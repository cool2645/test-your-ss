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

Route::get('/', 'HomeController@launch');
Route::get('/status', 'HomeController@status');
Route::get('/about', 'HomeController@about');

Route::get('api/jobs', 'JobController@getJobList');
Route::get('api/jobs/{id}', 'JobController@getJobStatus');
Route::get('api/jobs/queue', 'JobController@getQueuingJobs');
Route::get('api/jobs/{id}/log', 'JobController@showJobLog');
Route::post('api/jobs', 'JobController@createJob');
Route::get('api/jobs/{id}/judge', 'JobController@judge');

Route::group(['middleware' => 'apiauth'], function () {
    Route::post('api/jobs/{id}', 'JobController@assignJob');
    Route::post('api/jobs/{id}/log', 'JobController@syncJobLog');
});

Route::post('node/mu_api_v2', 'HttpHelper@getSSNodesByMuApiV2');
Route::post('node/2645network_ssr', 'HttpHelper@getSSRNodesBy2645NetWork');