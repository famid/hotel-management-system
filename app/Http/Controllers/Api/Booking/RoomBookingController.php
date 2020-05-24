<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Booking\CheckInRequest;
use App\Http\Services\Booking\RoomBookingService;
use Illuminate\Http\JsonResponse;

class RoomBookingController extends Controller
{
    protected $roomBookingService;

    /**
     * RoomBookingController constructor.
     */
    public function __construct() {
        $this->roomBookingService = new RoomBookingService();
    }

    /**
     * @param CheckInRequest $request
     * @return JsonResponse
     */
    public function checkInRoom(CheckInRequest $request) {
        $response = $this->roomBookingService->checkInRequestResponse($request->all());
        //$response = $this->roomBookingService->updateReservationStatus();
        return response()->json($response);
    }
}
