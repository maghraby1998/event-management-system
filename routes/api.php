<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/auth/register", [AuthController::class, "register"]);
Route::post("/auth/login", [AuthController::class, "login"]);

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('events')->group(function () {

        Route::get("/", [EventController::class, "events"]);

        Route::post("/", [EventController::class, "makeEvent"]);

        Route::post("/{eventId}/join", [EventController::class, "joinEvent"]);

        Route::post("/{eventId}/remove-user/{userId}", [EventController::class, "removeUser"]);

        Route::delete("/{eventId}", [EventController::class, "deleteEvent"]);

        Route::post("/{eventId}/exit-event", [EventController::class, "exitEvent"]);

        Route::post("/{eventId}/toggle-favourite", [EventController::class, "toggleFavourite"]);

    });


    Route::prefix("requests")->group(function () {

        Route::post("/", [RequestController::class, "makeRequest"]);

        Route::get("/my-requests", [RequestController::class, "myRequests"]);

        Route::post("/{requestId}/accept", [RequestController::class, "acceptRequest"]);

        Route::post("/{requestId}/reject", [RequestController::class, "rejectRequest"]);

        Route::post("/{requestId}/cancel", [RequestController::class, "cancelRequest"]);


    });


});