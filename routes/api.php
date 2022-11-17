<?php

 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\HotelsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\PassportAuthController;

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

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);
Route::post('logout', [PassportAuthController::class, 'logout'])->middleware('auth:api');
Route::get('authcheck', [PassportAuthController::class, 'index'])->middleware('auth:api');

 

Route::get('countries', [CountriesController::class, 'index']);
Route::get('countries/{id}', [CountriesController::class, 'show']);
Route::post('countries', [CountriesController::class, 'store'])->middleware('auth:api');
Route::put('countries/{id}', [CountriesController::class, 'update'])->middleware('auth:api');
Route::delete('countries/{id}', [CountriesController::class, 'destroy'])->middleware('auth:api');


Route::get('hotels', [HotelsController::class, 'index']);
Route::get('hotels/{id}', [HotelsController::class, 'show']);
Route::get('hotels/country/{id}', [HotelsController::class, 'byCountry']);
Route::get('hotels/sort/price', [HotelsController::class, 'sortByPrice']);
Route::get('hotels/search/{keyword}', [HotelsController::class, 'search']);
Route::post('hotels', [HotelsController::class, 'store'])->middleware('auth:api');
Route::post('hotels/{id}', [HotelsController::class, 'update'])->middleware('auth:api');
Route::delete('hotels/{id}', [HotelsController::class, 'destroy'])->middleware('auth:api');

Route::get('orders', [OrdersController::class, 'index'])->middleware('auth:api');
Route::get('orders/all', [OrdersController::class, 'all'])->middleware('auth:api');
Route::get('orders/{id}', [OrdersController::class, 'status'])->middleware('auth:api');
Route::post('orders', [OrdersController::class, 'store'])->middleware('auth:api');
Route::delete('orders/{id}', [OrdersController::class, 'destroy'])->middleware('auth:api');