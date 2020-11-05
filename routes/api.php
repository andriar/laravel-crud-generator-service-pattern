<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/oauth/login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:api')->post('/oauth/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth:api')->get('/user', [UserController::class, 'index']);
Route::middleware('auth:api')->post('/user', [UserController::class, 'store']);
Route::middleware('auth:api')->patch('/user/{id}', [UserController::class, 'update']);
Route::middleware('auth:api')->delete('/user/{id}', [UserController::class, 'delete']);
Route::middleware('auth:api')->delete('/user/{id}/permanent', [UserController::class, 'deletePermanent']);
Route::middleware('auth:api')->patch('/user/{id}/restore', [UserController::class, 'restore']);
Route::middleware('auth:api')->get('/user-withtrashed', [UserController::class, 'withtrashed']);
Route::middleware('auth:api')->get('/user-onlytrashed', [UserController::class, 'onlytrashed']);
