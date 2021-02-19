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

Route::any('process/{uri}', [\App\Http\Controllers\RequestController::class, 'process'])
    ->where('uri', '.*');

Route::get('status/{jobStatusId}', [\App\Http\Controllers\RequestController::class, 'status']);
