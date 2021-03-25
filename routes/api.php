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
	Route::post('/users/register', [UserController::class, 'register']);
    Route::post('/users/login', [UserController::class, 'login']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
	Route::get('/users/logout', [UserController::class, 'logout']);
	Route::post('/users/{userId}/events', [EventController::class, 'store'])
			->whereNumber('userId');
	Route::get('/users/{userId}/events', [EventController::class, 'index'])
			->whereNumber('userId');
	Route::get('/users/{userId}/events/{eventId}', [EventController::class, 'show'])
			->whereNumber('userId')
			->whereNumber('eventId');
	Route::delete('/users/{userId}/events/{eventId}', [EventController::class, 'destroy'])
			->whereNumber('userId')
			->whereNumber('eventId');
	Route::put('/users/{userId}/events/{eventId}', [EventController::class, 'update'])
			->whereNumber('userId')
			->whereNumber('eventId');
	Route::post('/users/{userId}/events/{eventId}/invitations', [InvitationController::class, 'store'])
			->whereNumber('userId')
			->whereNumber('eventId');
	Route::delete('/users/{userId}/events/{eventId}/invitations/{invitationId}', [InvitationController::class, 'destroy'])
			->whereNumber('userId')
			->whereNumber('eventId')
			->whereNumber('invitationId');
	Route::get('/event-locations', [EventController::class, 'locations']);
});
   