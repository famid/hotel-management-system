<?php


namespace App\Http\Repository;


use App\Models\Room;

class RoomRepository extends BaseRepository
{
    public $model;

    public function __construct() {
        $this->model = new Room();
        parent::__construct($this->model);
    }
}
