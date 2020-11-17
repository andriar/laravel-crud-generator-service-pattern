<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\CategoryController; 

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

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::patch('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'delete']);
    Route::delete('products/{id}/permanent', [ProductController::class, 'deletePermanent']);
    Route::patch('/products/{id}/restore', [ProductController::class, 'restore']);
    Route::get('products-withtrashed', [ProductController::class, 'withtrashed']);
    Route::get('products-onlytrashed', [ProductController::class, 'onlytrashed']);
    Route::patch('product-stocks/{productId}', [ProductController::class, 'updatestock']);
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
    Route::patch('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'delete']);
    Route::delete('categories/{id}/permanent', [CategoryController::class, 'deletePermanent']);
    Route::patch('categories/{id}/restore', [CategoryController::class, 'restore']);
    Route::get('categories-withtrashed', [CategoryController::class, 'withtrashed']);
    Route::get('categories-onlytrashed', [CategoryController::class, 'onlytrashed']);
});
