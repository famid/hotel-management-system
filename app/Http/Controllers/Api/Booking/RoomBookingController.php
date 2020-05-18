<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Booking\CheckInRequest;
use App\Http\Services\Booking\RoomBookingService;

class RoomBookingController extends Controller
{
    protected $roomBookingService;

    /**
     * RoomBookingController constructor.
     */
    public function __construct() {
        $this->roomBookingService = new RoomBookingService();
    }

    public function checkInRoom(CheckInRequest $request) {
        $response = $this->roomBookingService->booking($request->all());
        //$response = $this->roomBookingService->updateReservationStatus();
        return response()->json($response);
    }
}
