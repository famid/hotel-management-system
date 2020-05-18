<?php


namespace App\Http\Services\Hotel;


use App\Http\Repository\HotelFeatureRepository;
use Exception;

class HotelFeatureService
{
    protected $hotelFeatureRepository;
    protected $errorResponse;
    protected $errorMessage;

    /**
     * HotelFeatureService constructor.
     */
    public function __construct() {
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
    protected function hotelFeatureData(array $data){
        $hotelFeatureData = [];
        foreach ($data['feature'] as $key => $feature) {
            $arrayData = [
                'feature' => $feature,
                'hotel_id' => $data['hotel_id']
            ];
            array_push($hotelFeatureData, $arrayData);
        }

        return $hotelFeatureData;
    }

    /**
     * @param array $feature
     * @return array
     */
    public function create (array $feature) :array{
        try {
            $featureData = $this->hotelFeatureData($feature);
            if(!is_null($featureData)){
               foreach ($featureData as $key => $data) {
                   $createHotelFeatureResponse = $this->hotelFeatureRepository->create($data);
                   if(!$createHotelFeatureResponse) {

                       return $this->errorResponse;
                   }
               }

                return ['success' => true , 'message' => 'Hotel Feature was created successfully'];
            }

            return $this->errorResponse;
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param array $feature
     * @return array
     */
    public function update (array $feature) :array{
        try {
            $deleteAllFeatureResponse = $this->deleteFeatureByHotelId($feature['hotel_id']);
            if($deleteAllFeatureResponse['success']){
                $featureData = $this->hotelFeatureData($feature);
                if(!is_null($featureData)){
                    foreach ($featureData as $key => $data) {
                        $updateHotelFeatureResponse = $this->hotelFeatureRepository->create($data);
                        if(!$updateHotelFeatureResponse) {

                            return $this->errorResponse;
                        }
                    }

                    return ['success' => true , 'message' => 'Hotel Feature was updated successfully'];
                }
            }

            return $this->errorResponse;
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param int $hotelId
     * @return array
     */
    protected function deleteFeatureByHotelId (int $hotelId) :array{

        $where = ['hotel_id' => $hotelId];
        $deleteAllFeatureResponse = $this->hotelFeatureRepository->deleteWhere($where);
        if (!$deleteAllFeatureResponse) {

            return $this->errorResponse;
        }

        return ['success' => true , 'message' => 'All Feature of hotel deleted successfully'];
    }

    /**
     * @param int $id
     * @return array
     */
    public function delete (int $id) :array{
        $where = ['id' => $id];
        $deleteHotelFeatureResponse = $this->hotelFeatureRepository->deleteWhere($where);
        if(!$deleteHotelFeatureResponse) {

            return $this->errorResponse;
        }

        return ['success' => true , 'message' => 'Hotel was deleted successfully'];
    }
}
