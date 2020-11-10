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
Route::post('/oauth/register', [AuthController::class, 'register'])->name('register');
Route::post('/oauth/activation', [AuthController::class, 'activation'])->name('activation');
Route::middleware('auth:api')->post('/oauth/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user', [UserController::class, 'store']);
    Route::patch('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'delete']);
    Route::delete('/user/{id}/permanent', [UserController::class, 'deletePermanent']);
    Route::patch('/user/{id}/restore', [UserController::class, 'restore']);
    Route::get('/user-withtrashed', [UserController::class, 'withtrashed']);
    Route::get('/user-onlytrashed', [UserController::class, 'onlytrashed']);
});