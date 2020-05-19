<?php


namespace App\Http\Repository;


use App\Models\RoomImage;

class RoomImageRepository extends BaseRepository
{
    public $model;

    /**
     * RoomImageRepository constructor.
     */
    public function __construct() {
        $this->model = new RoomImage();
        parent::__construct($this->model);
    }

    /**
     * @param int $imageId
     * @return mixed
     */
    public function getImageName (int $imageId) {

        return $this->model::where('id',$imageId)->first()->picture;
    }

    public function getImageNameByRoomId (int $roomId) {

        return $this->model::select('picture')->where('room_id',$roomId)->get();
    }
}
