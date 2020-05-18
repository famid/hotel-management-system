<?php


namespace App\Http\Services\Hotel;


use App\Http\Repository\HotelDetailRepository;
use Exception;

class HotelDetailService
{
    protected $hotelDetailRepository;
    protected $errorResponse;
    protected $errorMessage;

    /**
     * HotelService constructor.
     */
    public function __construct()
    {
        $this->hotelDetailRepository = new HotelDetailRepository();
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
    public function create(array $data): array {
        try {
            $hotelDetailsData = $this->prepareHotelDetailsData($data);
            $createHotelResponse = $this->hotelDetailRepository->create($hotelDetailsData);
            if (!$createHotelResponse) {

                return $this->errorResponse;
            }

            return ['success' => true, 'message' => __('Hotel Details is created successfully')];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function update(array $data): array {
        try {
            $where = ['id' => $data['id']];
            $hotelDetailsData = $this->prepareHotelDetailsData($data);
            $updateHotelResponse = $this->hotelDetailRepository->update($where,$hotelDetailsData);
            if (!$updateHotelResponse) {

                return $this->errorResponse;
            }

            return ['success' => true, 'message' => __('Hotel Details are updated successfully')];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param array $data
     * @return array
     */
    protected function prepareHotelDetailsData (array $data) :array{

        return [
            'hotel_id' => $data['hotel_id'],
            'country' => $data['country'],
            'city' => $data['city'],
            'state' => $data['state'],
            'location' => $data['location'],
            'zip_code' => $data['zip_code'],
        ];
    }
}
