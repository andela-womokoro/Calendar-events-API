<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InvitationController;

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


Route::group(['middleware' => 'api'], function () {
	Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
	Route::get('/logout', [UserController::class, 'logout']);
	Route::post('/events', [EventController::class, 'store']);
	Route::get('/events', [EventController::class, 'index']);
	Route::get('/events/{eventId}', [EventController::class, 'show']);
	Route::delete('/events/{eventId}', [EventController::class, 'destroy']);
	Route::put('/events/{eventId}', [EventController::class, 'update']);
	Route::get('/event-locations', [EventController::class, 'locations']);
	Route::post('/events/{eventId}/invite', [InvitationController::class, 'store']);
});
   