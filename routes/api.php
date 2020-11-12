<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MerchantController;

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
Route::post('/oauth/register', [AuthController::class, 'register'])->name('register');
Route::post('/oauth/activation', [AuthController::class, 'activation'])->name('activation');
Route::middleware('auth:api')->post('/oauth/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::patch('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'delete']);
    Route::delete('/users/{id}/permanent', [UserController::class, 'deletePermanent']);
    Route::patch('/users/{id}/restore', [UserController::class, 'restore']);
    Route::get('/users-withtrashed', [UserController::class, 'withtrashed']);
    Route::get('/users-onlytrashed', [UserController::class, 'onlytrashed']);
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/merchants', [MerchantController::class, 'index']);
    Route::post('/merchants', [MerchantController::class, 'store']);
    Route::patch('/merchants/{id}', [MerchantController::class, 'update']);
    Route::delete('/merchants/{id}', [MerchantController::class, 'delete']);
});