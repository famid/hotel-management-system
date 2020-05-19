<?php


namespace App\Http\Services\Hotel;


use App\Http\Repository\HotelFeatureRepository;
use App\Http\Repository\HotelRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

class HotelService
{
    protected $hotelRepository;
    protected $hotelFeatureRepository;
    protected $errorResponse;
    protected $errorMessage;

    /**
     * HotelService constructor.
     */
    public function __construct() {
        $this->hotelRepository = new HotelRepository();
        $this->hotelFeatureRepository = new HotelFeatureRepository();
        $this->errorMessage = __('Something went wrong');
        $this->errorResponse = [
            'success' => false,
            'data' => [],
            'message' => $this->errorMessage,
            'webResponse' => [
                'dismiss' => $this->errorMessage
            ]
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function create (array $data) :array{
        $id =Auth::id();
        try {
            $data = [
                'name' => $data['name'],
                'star_rating' => $data['star_rating'],
                'user_id' => $id
            ];
            $createHotelResponse = $this->hotelRepository->create($data);
           if(!$createHotelResponse) {

               return $this->errorResponse;
           }

            return ['success' => true , 'message' => __('Hotel was created successfully')];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function update (array $data) :array{
        $id =Auth::id();
        try {
            $where = ['id' => $data['id']];
            $data = [
                'name' => $data['name'],
                'star_rating' => $data['star_rating'],
                'user_id' => $id
            ];
            $updateHotelResponse = $this->hotelRepository->update($where,$data);
            if(!$updateHotelResponse) {

                return $this->errorResponse;
            }

            return ['success' => true , 'message' => __('Hotel was updated successfully')];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param int $id
     * @return array
     */
    public function delete(int $id):array {
        try {
            $where = ['id' => $id];
            $deleteHotelResponse = $this->hotelRepository->deleteWhere($where);
            if(!$deleteHotelResponse) {

                return $this->errorResponse;
            }

            return ['success' => true , 'message' => __('Hotel was deleted successfully')];

        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @return array
     */
    public function  allHotel () :array {
        try {
            $allHotelData = $this->hotelRepository->getAll();
            if($allHotelData->isEmpty()) {

                return $this->errorResponse;
            }

            return ['success' => true, 'data' => $allHotelData, 'message' => __('All Hotel are found successfully')];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param int $id
     * @return array
     */
    public function getHotelDetails(int $id) :array{
        try {
            $hotelDetails['details'] = $this->hotelRepository->hotelDetailsById($id);
            $hotelDetails['features'] = $this->hotelFeatureRepository->getFeatureByHotelId($id);
            if ($hotelDetails['details']->isEmpty()) {

                return $this->errorResponse;
            }

            return ['success' => true, 'data' => $hotelDetails, 'message' => __('Hotel Details is found successfully')];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function filter (array $data) :array {
        try {
            $where = $this->prepareFilterData($data);
            $filterResponse = $this->hotelRepository->filter($where);
            if ($filterResponse->isEmpty()) {

                return ['success' => false, 'message' => __('No Result Found')];
            }

            return ['success' => true, 'data' => $filterResponse, 'message' => __('Filter Result found successfully')];

        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param $data
     * @return array|string[]
     */
    protected  function prepareFilterData($data) {
        $where = [];
        if($data['name']) {
            $where =[['name', 'Like', '%' . $data['name'] . '%']];
        }else {
            if($data['star_rating'])   {$where['hotels.star_rating'] = $data['star_rating'];}
            if($data['country'])       {$where['hotel_details.country'] = $data['country'];}
            if($data['city'])          {$where['hotel_details.city'] = $data['city'];}
            if($data['state'])         {$where['hotel_details.state'] = $data['state'];}
            if($data['location'])      {$where['hotel_details.location'] = $data['location'];}

        }

        return $where;
    }



}
