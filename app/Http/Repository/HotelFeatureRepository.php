<?php


namespace App\Http\Repository;


use App\Models\HotelFeature;

class HotelFeatureRepository extends  BaseRepository
{
    public $model;

    /**
     * UserRepository constructor.
     */
    public function __construct() {
        $this->model = new HotelFeature();
        parent::__construct($this->model);
    }
    public function getFeatureByHotelId(int $id) {
        return   $this->model::where('hotel_id',$id)->pluck('feature');

    }

}
