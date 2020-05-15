<?php


namespace App\Http\Services\Booking;


use App\Http\Repository\RoomBookingRepository;

class RoomBookingService
{
    protected $roomBookingRepository;
    protected $errorResponse;
    protected $errorMessage;

    public function __construct() {
        $this->roomBookingRepository = new RoomBookingRepository();
        $this->errorMessage = __('Something went wrong');
        $this->errorResponse = [
            'success' => false,
            'data' => [],
            'message' => $this->errorMessage,
            'webResponse' => [
                'dismiss' => $this->errorMessage
            ]
        ];
    }
}