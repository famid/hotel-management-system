<?php


namespace App\Http\Repository;


use App\Models\Hotel;

class HotelRepository extends BaseRepository
{
    public $model;

    /**
     * UserRepository constructor.
     */
    public function __construct() {
        $this->model = new Hotel();
        parent::__construct($this->model);
    }
}
