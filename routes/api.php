<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MessageController;
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

        Route::get("/{eventId}", [EventController::class, "getEventDetails"]);

        Route::post("/{eventId}/join", [EventController::class, "joinEvent"]);

        Route::post("/{eventId}/remove-user/{userId}", [EventController::class, "removeUser"]);

        Route::delete("/{eventId}", [EventController::class, "deleteEvent"]);

        Route::post("/{eventId}/exit-event", [EventController::class, "exitEvent"]);

        Route::post("/{eventId}/toggle-favourite", [EventController::class, "toggleFavourite"]);

        Route::post("/{eventId}/request-to-join", [EventController::class, "requestToJoinEvent"]);

        Route::get("/{eventId}/users-to-invite", [EventController::class, "getUsersToInvite"]);


    });


    Route::prefix("requests")->group(function () {

        Route::post("/", [RequestController::class, "makeRequest"]);

        Route::get("/my-requests", [RequestController::class, "myRequests"]);

        Route::get("/my-received-requests", [RequestController::class, "myReceivedRequests"]);

        Route::post("/{requestId}/accept", [RequestController::class, "acceptRequest"]);

        Route::post("/{requestId}/reject", [RequestController::class, "rejectRequest"]);

        Route::post("/{requestId}/cancel", [RequestController::class, "cancelRequest"]);


    });

    Route::prefix("invitations")->group(function () {
        Route::get("/received", [InvitationController::class, "getReceivedInvitations"]);
        Route::get("/sent", [InvitationController::class, "getSentInvitations"]);
        Route::post("/send", [InvitationController::class, "inviteUsers"]);
    });


    Route::prefix("chats")->group(function () {
        Route::get("/", [ChatController::class, "getMyChats"]);
    });

    Route::prefix("messages")->group(function () {
        Route::get("/{chatId}", [MessageController::class, "getChatMessages"]);
        Route::post("/", [MessageController::class, "sendMessage"]);
    });



});

Route::get("/verify-email/{userId}/{token}", [AuthController::class, "verifyEmail"])->name("verify.email");




// running SQL queries
////////////////////////
// DB::select
// DB::insert
// DB::update
// DB::delete
// DB::scalar -> to return a single value ex: (count(*))




// query builder
////////////////////////
// DB::table()->get();
// DB::table()->find();

Route::get("test", function () {
    $migrationsWithCount = DB::table("migrations")->select(DB::raw("count(*) as total_number, id"))->where("id", "<=", "10")->orderBy("id")->groupBy("id")->get();
    return $migrationsWithCount;
});