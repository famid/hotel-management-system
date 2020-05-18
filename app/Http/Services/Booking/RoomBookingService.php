<?php


namespace App\Http\Services\Booking;


use App\Http\Repository\RoomBookingRepository;
use App\Http\Repository\RoomRepository;
use App\Http\Services\Revenue\RevenueService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoomBookingService
{
    protected $roomBookingRepository;
    protected $roomRepository;
    protected $revenueService;
    protected $errorResponse;
    protected $errorMessage;

    /**
     * RoomBookingService constructor.
     */
    public function __construct()
    {
        $this->roomBookingRepository = new RoomBookingRepository();
        $this->roomRepository = new RoomRepository();
        $this->revenueService = new RevenueService();
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
     * @param int $roomId
     * @return bool
     */
    public function validRoomIdCheck(int $roomId) {
        $validRoomStatus = $this->roomRepository->isRoomExist($roomId);

        return !$validRoomStatus ? false : true;
    }

    /**
     * @param string $checkIn
     * @return bool
     */
    public function validRoomCheckInDate (string $checkIn) {

     return Carbon::parse($checkIn)->lt(Carbon::today()) ? false : true;
    }

    /**
     *This function is for corn job
     * @return array
     */
    public function updateReservationStatus() {
        try {
            $roomData = $this->roomRepository->updateBookedRoomList();
            if ($roomData->isEmpty()) {

                return $this->errorResponse;
            }
            foreach ($roomData as $key => $room) {
                $roomId = $room->room_id;
                $roomCheckIn = Carbon::parse($room->check_in);
                $roomCheckOut = Carbon::parse($room->check_out);
                $this->changeStatus($roomId, $roomCheckIn, $roomCheckOut);
            }

            return ['success' => true, 'message' => 'Room Status is updated successfully'];
        } catch (Exception $e) {
            Log::info($e);
            return $this->errorResponse;
        }
    }

    /**
     * @param int $roomId
     * @param $roomCheckIn
     * @param $roomCheckOut
     * @return bool
     */
    protected function changeStatus($roomId, $roomCheckIn, $roomCheckOut) :bool{
        $checkResponse = Carbon::now()->between($roomCheckIn, $roomCheckOut);
        if (!$checkResponse) {
            $updateResponse = $this->roomRepository->updateRoomStatus($roomId, true);
        } else {
            $updateResponse =$this->roomRepository->updateRoomStatus($roomId, false);
        }

        return !$updateResponse ? false : true;
    }

    /**
     * @param array $data
     * @return array
     */
    public function booking(array $data): array {
        try {
            DB::beginTransaction();
            $store = $this->prepareRoomBookingData($data);
            $availableStatus = $this->isRoomAvailable($store['room_id'], $store['check_in'], $store['check_out']);
            if (!$availableStatus) {
                DB::rollBack();

                return ['success' => false, 'message' => __('This Room is not available Now')];
            }
            $roomBookedResponse = $this->roomBookingRepository->create($store);
            if (!$roomBookedResponse) {
                DB::rollBack();

                return $this->errorResponse;
            }
            $updateAvailableDateResponse = $this->updateRoomAvailableDate($store['room_id']);
            if(!$updateAvailableDateResponse) {
                DB::rollBack();

                return $this->errorResponse;
            }
            $revenueStoreResponse = $this->revenueService->store($store['room_id'], $store['paid_amount']);
            if (!$revenueStoreResponse['success']) {
                DB::rollBack();

                return $this->errorResponse;
            }
            DB::commit();

            return ['success' => true, 'message' => 'Room is Booked successfully'];
        } catch (Exception $e) {
            DB::rollBack();

            return $this->errorResponse;
        }
    }

    /**
     * @param $data
     * @return array
     */
    protected function prepareRoomBookingData(array $data) {
        $rent = $this->roomRepository->getRoomRentById($data['room_id']);
        $paymentStatus = ($rent == $data['paid_amount']) ? PAID : DUE;
        $checkIn = Carbon::parse($data['check_in']);
        $durationOfDays = $data['duration_of_stay'];
        $checkOut = Carbon::parse($data['check_in'])->addDays($durationOfDays);

        return [
            'user_id' => Auth::id(),
            'room_id' => $data['room_id'],
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'paid_amount' => $data['paid_amount'],
            'payment_status' => $paymentStatus,
        ];
    }

    /**
     * @param $roomId
     * @param $userCheckIn
     * @param $userCheckOut
     * @return bool
     */
    private function isRoomAvailable($roomId, $userCheckIn, $userCheckOut) :bool{
        $check = true;
        $roomData = $this->roomRepository->BookedRoomList($roomId);
        foreach ($roomData as $key => $room) {
            $roomCheckIn = Carbon::parse($room->check_in);
            $roomCheckOut = Carbon::parse($room->check_out);
            $checkInResponse = $userCheckIn->between($roomCheckIn, $roomCheckOut);
            $checkOutResponse = $userCheckOut->between($roomCheckIn, $roomCheckOut);
            $userCheckInResponse = $roomCheckIn->between($userCheckIn, $userCheckOut);
            $userCheckOutResponse = ($roomCheckOut->between($userCheckIn, $userCheckOut));

            if ($checkInResponse === true || $checkOutResponse === true || $userCheckInResponse ===
                true || $userCheckOutResponse === true) {
                $check = false;
                break;
            } else {
                $check = true;
            }
        }

        return $check;
    }

    /**
     * @param $roomId
     * @return bool
     */
    private function updateRoomAvailableDate($roomId) {
        $availableDate = $this->roomBookingRepository->getAvailableAt($roomId);
        $updateResponse = $this->roomRepository->updateAvailableAt($roomId, $availableDate);

        return !$updateResponse? false : true;
    }
}
/* public function updateReservationStatus() {
         try {
             $reservedRoomListResponse = $this->getReservedRoomList();
             if (!$reservedRoomListResponse['success']){

                 return $this->errorResponse;
             }
             foreach ($reservedRoomListResponse['data'] as $key => $room) {
                 $roomId = $room['room_id'];
                 $roomCheckIn = Carbon::parse($room['check_in']);
                 $roomCheckOut = Carbon::parse($room['check_out']);
                 $this->changeStatus($roomId,$roomCheckIn,$roomCheckOut);

             }
             return ['success' => true, 'message' => 'Room Status is updated successfully'];
         } catch (Exception $e) {

             return $this->errorResponse;
         }
     }

     private function getReservedRoomList (int $roomId = null) {
         $roomData = $this->roomRepository->BookedRoomList($roomId);
         $allRoomData = [];
         if($roomData->isEmpty()) {

             return ['success' => false , 'data' => $allRoomData];
         }
         foreach($roomData as $key => $room){
            $roomData= [
                'room_id' => $room->room_id,
                'check_in' => Carbon::parse($room->check_in),
                'check_out' => Carbon::parse($room->check_out),
            ];
            array_push($allRoomData,$roomData);
         }

         return ['success' => true, 'data' => $allRoomData];
     }*/