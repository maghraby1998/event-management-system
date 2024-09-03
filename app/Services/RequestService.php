<?php

namespace App\Services;

use App\Jobs\UserJoinEventJob;
use App\Models\Event;
use App\Models\Request;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB; // Import the DB facade



class RequestService
{

	public static function myRequests($request)
	{
		$user = User::findOrFail(auth()->id());

		return $user->requests()->filter(['status' => $request->status])->select(['id', 'event_id', 'user_id', 'status', 'created_at'])->with('event:id,name')->get()->map(function ($request) {
			$request->canCancel = $request->canCancel();
			return $request;
		});
	}

	public static function myReceivedRequests()
	{

		$authId = auth()->id();

		// query builder is the fastest (same as raw)

		// return DB::table("requests")->join("events", "requests.event_id", "=", "events.id")->where("events.created_by", $authId)->get();

		// $sql = "SELECT * FROM requests r
		// 		JOIN events e
		// 		ON r.event_id = e.id
		// 		where e.created_by = ${authId}
		// 		";

		// $result = DB::select($sql);

		// return array_map(function ($row) {
		// 	return [
		// 		"id" => $row->id,
		// 		"event" => [
		// 			"name" => $row->name,
		// 		],
		// 		"status" => $row->status
		// 	];
		// }, $result);


		// orm is the most flexible and powerful but a bit slower

		return Request::whereHas("event", function ($query) use ($authId) {
			$query->where("created_by", $authId);
		})->select(['id', 'event_id', 'user_id', 'status', 'created_at'])->with('event:id,name,created_by')->get()->map(function ($request) {
			$request->canAccept = $request->canAcceptOrReject();
			$request->canReject = $request->canAcceptOrReject();
			return $request;
		});
	}

	public static function acceptRequest($requestId)
	{

		$request = Request::findOrFail($requestId);

		if (!Gate::allows("acceptRequest", $request)) {
			abort(403);
		}



		if ($request->status != "pending") {

			return response()->json([
				'message' => "you can't accept this request because this request is {$request->status}"
			], 400);

		} else {

			$request->status = "accepted";

			$request->save();

			UserJoinEventJob::dispatch($request->user_id, $request->event_id);

			return response()->json([
				"message" => "request has been succesfully accepted"
			]);

		}

	}

	public static function rejectRequest($requestId)
	{
		$request = Request::findOrFail($requestId);

		if (!Gate::allows("rejectRequest", $request)) {
			abort(403);
		}

		if ($request->status != "pending") {
			return response()->json([
				"message" => "you can't reject this request because this request is {$request->status}"
			]);
		} else {

			$request->status = "rejected";

			$request->save();

			return response()->json([
				"message" => "request has been rejected"
			]);
		}
	}

	public static function cancelRequest($requestId)
	{
		$request = Request::findOrFail($requestId);

		if (!Gate::allows("cancelRequest", $request)) {
			abort(403);
		}

		if ($request->user_id != auth()->id()) {
			return response()->json([
				"message" => "you can't cancel this request"
			], 400);
		} else {

			if ($request->status != "pending") {
				return response()->json([
					"message" => "you can't cancel this request becaues this request is {$request->status}"
				], 400);
			} else {
				$request->status = "canceled";

				$request->save();

				return response()->json([
					"message" => "request has been cancelled"
				]);
			}
		}
	}

}