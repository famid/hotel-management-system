<?php


namespace App\Http\Repository;


use App\Models\Room;
use Carbon\Carbon;


class RoomRepository extends BaseRepository
{
    public $model;

    public function __construct()
    {
        $this->model = new Room();
        parent::__construct($this->model);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getRoomRentById(int $id)
    {

        return $this->model::where('id', $id)->first()->rent;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function isRoomExist(int $id) {

        return $this->model::where('id', '=', $id)->exists();
    }


    /**
     * @param int $roomId
     * @param bool $status
     * @return mixed
     */
    public function updateRoomStatus(int $roomId,$status){

        return $this->model::where('id', $roomId)->update([
            'reservation_status' => $status
        ]);
    }

    /**
     * @param int $roomId
     * @param $availableDate
     * @return mixed
     */
    public function updateAvailableAt(int $roomId, $availableDate){

        return $this->model::where('id', $roomId)->update([
            'available_at' => $availableDate
        ]);
    }

    /**
     * @param $roomId
     * @return mixed
     */
    public function checkRoomStatus($roomId) {

        return $this->model::where('id',2)->where('reservation_status', '=', 0)->exists();
    }

    /**
     * @param null $roomId
     * @return mixed
     */
    public function BookedRoomList ($roomId = null) {
        $data =$this->model::select(
            'rooms.id as room_id',
            'rooms.reservation_status as reservation_status',
            'rooms.available_at as available_at',
            'room_bookings.check_in as check_in',
            'room_bookings.check_out as check_out'
        )
            ->leftJoin('room_bookings', ['rooms.id' => 'room_bookings.room_id']);

        return ((!is_null($roomId))) ? $data->where('rooms.id', $roomId)->get() : $data->get();

    }
    public function updateBookedRoomList ($roomId = null) {
        return $this->model::select(
            'rooms.id as room_id',
            'rooms.reservation_status as reservation_status',
            'rooms.available_at as available_at',
            'room_bookings.check_in as check_in',
            'room_bookings.check_out as check_out'
        )
            ->leftJoin('room_bookings', ['rooms.id' => 'room_bookings.room_id'])
            ->whereDate('check_in' ,'<=', Carbon::now())
            //->where('rooms.reservation_status', '=', false)
            ->get();
    }
}
