<?php


namespace App\Http\Services\Revenue;


use App\Http\Repository\HotelRepository;
use App\Http\Repository\RevenueRepository;
use App\Http\Repository\RoomRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

class RevenueService
{
    protected $revenueRepository;
    protected $roomRepository;
    protected $hotelRepository;
    protected $errorResponse;
    protected $errorMessage;

    /**
     * RevenueService constructor.
     */
    public function __construct() {
        $this->revenueRepository = new RevenueRepository();
        $this->roomRepository = new RoomRepository();
        $this->hotelRepository = new HotelRepository();
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
     * @param $roomId
     * @param $paidAmount
     * @return array
     */
    protected function prepareRevenueData($roomId, $paidAmount) {
         $hotelId = $this->roomRepository->getHotelIdByRoomId($roomId);
         $userId = $this->hotelRepository->getUserIdByHotelId($hotelId);
         $amount = $paidAmount;

         return [
           'amount' => $amount,
           'hotel_id' => $hotelId,
           'room_id' => $roomId,
           'user_id' => $userId
         ];
    }

    /**
     * @param int $roomId
     * @param float $paidAmount
     * @return array
     */
    public function store (int $roomId, float $paidAmount) :array{
        try {
           $data = $this->prepareRevenueData($roomId,$paidAmount);
           $createResponse =  $this->revenueRepository->create($data);
           if (!$createResponse) {

              return  $this->errorResponse;
           }

           return ['success' => true, 'message' => __('Revenue Table is updated')];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @return array
     */
    public function userRevenueInfo () {
        try {
            $revenueInfo = $this->revenueRepository->RevenueInfoByUserId(Auth::id());
            if(empty($revenueInfo)) {

                return ['success' => true, 'data' => $revenueInfo,'message' => __('Revenue Info is not Available')];
            }

            return ['success' => true, 'data' => $revenueInfo,'message' => __('Revenue Info is found')];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }
}