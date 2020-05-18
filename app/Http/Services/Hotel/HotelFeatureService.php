<?php


namespace App\Http\Services\Hotel;


use App\Http\Repository\HotelFeatureRepository;
use Exception;
use Illuminate\Support\Facades\DB;

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
            if(empty($featureData)) {

                return $this->errorResponse;
            }
           foreach ($featureData as $key => $data) {
               $createHotelFeatureResponse = $this->hotelFeatureRepository->create($data);
               if(!$createHotelFeatureResponse) {

                   return $this->errorResponse;
               }
           }

            return ['success' => true , 'message' => __('Hotel Feature was created successfully')];
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
            DB::beginTransaction();
            $deleteAllFeatureResponse = $this->deleteFeatureByHotelId($feature['hotel_id']);
            if(!$deleteAllFeatureResponse['success']){
                DB::rollBack();

                return $this->errorResponse;
            }
                $featureData = $this->hotelFeatureData($feature);
            if(empty($featureData)) {
                DB::rollBack();

                return $this->errorResponse;
            }
            foreach ($featureData as $key => $data) {
                $createHotelFeatureResponse = $this->hotelFeatureRepository->create($data);
                if(!$createHotelFeatureResponse) {
                    DB::rollBack();

                    return $this->errorResponse;
                }
            }
            DB::commit();

            return ['success' => true , 'message' => __('Hotel Feature was updated successfully')];
        } catch (Exception $e) {
            DB::rollBack();

            return $this->errorResponse;
        }
    }

    /**
     * @param int $hotelId
     * @return array
     */
    protected function deleteFeatureByHotelId (int $hotelId) :array{
        try {
            $where = ['hotel_id' => $hotelId];
            $deleteAllFeatureResponse = $this->hotelFeatureRepository->deleteWhere($where);
            if (!$deleteAllFeatureResponse) {

                return $this->errorResponse;
            }

            return ['success' => true , 'message' => __('All Feature of hotel deleted successfully')];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param int $id
     * @return array
     */
    public function delete (int $id) :array{
        try {
            $where = ['id' => $id];
            $deleteHotelFeatureResponse = $this->hotelFeatureRepository->deleteWhere($where);
            if(!$deleteHotelFeatureResponse) {

                return $this->errorResponse;
            }

            return ['success' => true , 'message' => __('Hotel was deleted successfully')];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }
}
