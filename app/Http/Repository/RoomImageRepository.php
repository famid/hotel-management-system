<?php


namespace App\Http\Repository;


use App\Models\RoomImage;

class RoomImageRepository extends BaseRepository
{
    public $model;

    public function __construct() {
        $this->model = new RoomImage();
        parent::__construct($this->model);
    }
}
