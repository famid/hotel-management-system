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

    /**
     * @param int $id
     * @return mixed
     */
    public function hotelDetailsById(int $id) {
        return $this->model::select(
            'hotels.id as hotel_id',
            'hotels.name as hotel_name',
            'hotels.star_rating as hotel_star_rating',
            'hotel_details.country as hotel_country',
            'hotel_details.city as hotel_city',
            'hotel_details.state as hotel_state ',
            'hotel_details.location as hotel_location ',
            'hotel_details.zip_code as hotel_zip_code '

        )
            ->leftJoin('hotel_details', ['hotels.id' => 'hotel_details.hotel_id'])
            //->leftJoin('hotel_features', ['hotels.id' => 'hotel_features.hotel_id'])
            ->where('hotels.id',$id)
           // ->distinct('hotels.id')
            ->get();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getUserIdByHotelId(int $id) {

        return $this->model::where('id',$id)->first()->user_id;
    }

    /**
     * @param $where
     * @return mixed
     */
    public function filter($where)
    {
        return Hotel::select([
            'hotels.name as name',
            'hotels.star_rating as star_rating',
            'hotel_details.location as location',
        ])
            ->leftjoin('hotel_details', ['hotel_details.hotel_id' => 'hotels.id'])
            ->leftjoin('hotel_features', ['hotel_features.hotel_id' => 'hotels.id'])
            ->where($where)
            ->distinct('hotels.id')
            ->get();
    }

}
