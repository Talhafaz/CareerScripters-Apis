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
    return response('{"status":404,"message":"Not Found"}',404);
});
Route::get('/no-auth', function () {
    return response('{"status":401,"message":"Unauthorized"}', 401);
});
