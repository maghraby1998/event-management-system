<?php

namespace App\Services;

use App\Jobs\UserJoinEventJob;
use App\Models\Event;
use App\Models\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;


class RequestService
{

	public static function myRequests($request)
	{
		$user = User::findOrFail(auth()->id());

		return $user->requests()->filter(['status' => $request->status])->select(['id', 'event_id', 'status', 'created_at'])->with('event:id,name,from,to')->get();
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
				$request->status = "cancelled";

				// $request->save();

				return response()->json([
					"message" => "request has been cancelled"
				]);
			}
		}
	}

}