<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestController;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::any('process/{uri}', [RequestController::class, 'process'])
    ->where('uri', '.*');

Route::get('status/{jobStatusId}', [RequestController::class, 'status']);

// TODO: Created for demo purpose only. Remove after demo.
Route::any('test/{status}', [RequestController::class, 'test']);


