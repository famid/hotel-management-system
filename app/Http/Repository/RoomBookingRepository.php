<?php


namespace App\Http\Repository;


use App\Models\RoomBooking;

class RoomBookingRepository extends BaseRepository
{
    public $model;

    public function __construct(){
        $this->model = new RoomBooking();
        parent::__construct($this->model);
    }
    public function getAvailableAt ($roomId) {
        return $this->model::where('room_id', $roomId)->orderBy('check_out', 'desc')->first()
            ->check_out;


    }

}
