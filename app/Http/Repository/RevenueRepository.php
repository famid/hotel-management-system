<?php


namespace App\Http\Repository;


use App\Models\Revenue;

class RevenueRepository extends  BaseRepository
{
    public $model;

    /**
     * RevenueRepository constructor.
     */
    public function __construct() {
        $this->model = new Revenue();
        parent::__construct($this->model );
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function RevenueInfoByUserId(int $userId)
    {
        return Revenue::select([
            'hotels.name as hotel',
            'rooms.room_number as room_no',
            'rooms.floor_no as floor',
            'revenues.amount as revenue',
            'revenues.created_at as date',
        ])
            ->leftjoin('hotels', ['revenues.hotel_id' => 'hotels.id'])
            ->leftjoin('rooms', ['revenues.room_id' => 'rooms.id'])
            ->where('hotels.user_id', $userId)
            ->orderBy('revenues.id', 'desc')
            ->get()->toArray();
    }

}
