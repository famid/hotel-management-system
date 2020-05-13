<?php


namespace App\Http\Repository;


use App\Models\HotelDetail;

class HotelDetailRepository extends  BaseRepository
{
    public $model;

    /**
     * UserRepository constructor.
     */
    public function __construct() {
        $this->model = new HotelDetail();
        parent::__construct($this->model);
    }
}
