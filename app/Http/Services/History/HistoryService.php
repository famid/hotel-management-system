<?php


namespace App\Http\Services\History;



use App\Http\Repository\RoomBookingRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

class HistoryService
{
    protected $errorResponse;
    protected $errorMessage;
    protected $roomBookingRepository;

    /**
     * HistoryService constructor.
     */
    public function __construct() {
        $this->roomBookingRepository = new RoomBookingRepository();
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
    public function userExpenditureInfo() {
        try {
            $expenditureData = $this->roomBookingRepository->allExpenditureInfo(Auth::id());
            if($expenditureData->isEmpty()) {

                return $this->errorResponse;
            }
            return ['success' => true, 'data' => $expenditureData, 'message' => __('ExpenditureInfo is found successfully') ];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

}