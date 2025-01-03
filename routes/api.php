<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RequestController;
use App\Services\FCMService;
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

Route::middleware(\App\Http\Middleware\TestMiddleware::class)->group(function () {
    Route::get("testing", function () {
        dd("is it working");
    });
});

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
        Route::post("/{invitationId}/accept", [InvitationController::class, "acceptInvitation"]);
        Route::post("/{invitationId}/reject", [InvitationController::class, "rejectInvitation"]);
        Route::post("/{invitationId}/cancel", [InvitationController::class, "cancelInvitation"]);
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

Route::get("notify", function () {
    $fcmService = new FCMService();

    $fcmService->sendNotification(["c8YNNL5Rm3owkJfv0fwiw_:APA91bFtRJJkfA_E69R32sm3dSBWIlVcdj1JsquTVv4s5sVMFr36be1pM4k5gcYQtx8WCfM_Z8DtTWb4DWFlxMgRXq_ewl1kaheTA4ttARmg-XkYSjs0IzUTm10qvY-6mhIKetWyB06u"], "hello from laravel", "is it working ?");
});

Route::get("/another-one", function () {
    return (\App\Models\Invitation::select(["id", "event_id", "sender_id"])->with("sender:id,name")->with("event:id,name")->first());
    dd(\DB::table("invitations")
        ->join("users", "users.id", "=", "invitations.sender_id")
        ->join("events", "events.id", "=", "invitations.event_id")
        ->select(["invitations.id as invitation_id", "users.id as userId", "users.name as username", "events.name as eventName"])
        ->get());
});
