<?php


namespace App\Http\Repository;


use App\Models\RoomBooking;

class RoomBookingRepository extends BaseRepository
{
    public $model;

    /**
     * RoomBookingRepository constructor.
     */
    public function __construct(){
        $this->model = new RoomBooking();
        parent::__construct($this->model);
    }

    /**
     * @param $roomId
     * @return mixed
     */
    public function getAvailableAt ($roomId) {

        return $this->model::where('room_id', $roomId)->orderBy('check_out', 'desc')->first()
            ->check_out;
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function allExpenditureInfo(int $userId) {
        return $this->model::select([
            'hotels.name as hotel',
            'rooms.room_number as room_no',
            'rooms.floor_no as floor',
            'room_bookings.check_in as check_in',
            'room_bookings.check_out as check_out',
            'room_bookings.paid_amount as paid_amount',
        ])
            ->leftjoin('rooms', ['room_bookings.room_id' => 'rooms.id'])
            ->leftjoin('hotels', ['rooms.hotel_id' => 'hotels.id'])
            ->where('room_bookings.user_id', $userId)
            ->orderBy('room_bookings.id', 'desc')
            ->get();
    }
}
