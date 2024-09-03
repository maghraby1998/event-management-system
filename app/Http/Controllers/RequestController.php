<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RequestService;
use App\Http\Requests\MakeRequestValidator;

class RequestController extends Controller
{

    /**
     * register.
     *
     * @OA\Get(
     *      path="/api/requests",
     *      operationId="get my requests",
     *      tags={"Requests"},
     *      summary="get my requests",
     *      description="get my requests",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="failed requests"
     *      )
     * )
     */
    public function myRequests(Request $request)
    {
        return RequestService::myRequests($request);
    }

    public function myReceivedRequests(Request $request)
    {
        return RequestService::myReceivedRequests($request);
    }

    /**
     * register.
     *
     * @OA\Post(
     *      path="/api/requests/{requestId}/accept",
     *      operationId="accept request",
     *      tags={"Requests"},
     *      summary="accept request",
     *      description="accept request",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="failed requests"
     *      )
     * )
     */
    public function acceptRequest($requestId)
    {
        return RequestService::acceptRequest($requestId);
    }

    /**
     * register.
     *
     * @OA\Post(
     *      path="/api/requests/{requestId}/reject",
     *      operationId="reject request",
     *      tags={"Requests"},
     *      summary="reject request",
     *      description="reject request",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="failed requests"
     *      )
     * )
     */
    public function rejectRequest($requestId)
    {
        return RequestService::rejectRequest($requestId);
    }

    /**
     * register.
     *
     * @OA\Post(
     *      path="/api/requests/{requestId}/cancel",
     *      operationId="cancel request",
     *      tags={"Requests"},
     *      summary="cancel request",
     *      description="cancel request",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="failed requests"
     *      )
     * )
     */
    public function cancelRequest($requestId)
    {
        return RequestService::cancelRequest($requestId);
    }
}
