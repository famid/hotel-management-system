<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Requests\Api\Hotel\CreateHotelRequest;
use App\Http\Requests\Api\Hotel\DeleteHotelRequest;
use App\Http\Requests\Api\Hotel\UpdateHotelRequest;
use App\Http\Services\Hotel\HotelService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class HotelController extends Controller
{
    protected $hotelService;

    /**
     * HotelController constructor.
     */
    public function __construct() {
        $this->hotelService = new HotelService();
    }

    /**
     * @param CreateHotelRequest $request
     * @return JsonResponse
     */
    public function createHotel(CreateHotelRequest $request) {
        $response = $this->hotelService->create($request->all());

        return response()->json($response);
    }

    /**
     * @param UpdateHotelRequest $request
     * @return JsonResponse
     */
    public function updateHotel (UpdateHotelRequest $request) {
        $response = $this->hotelService->update($request->all());

        return response()->json($response);
    }

    /**
     * @param DeleteHotelRequest $request
     * @return JsonResponse
     */
    public function deleteHotel (DeleteHotelRequest $request) {
        $response = $this->hotelService->delete($request->id);

        return response()->json($response);
    }
}
