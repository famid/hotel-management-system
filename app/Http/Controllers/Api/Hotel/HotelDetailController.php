<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Requests\Api\Hotel\CreateHotelDetailRequest;
use App\Http\Services\Hotel\HotelDetailService;
use App\Http\Controllers\Controller;

class HotelDetailController extends Controller
{
    protected $hotelDetailService;

    /**
     * HotelDetailController constructor.
     */
    public function __construct() {
        $this->hotelDetailService = new HotelDetailService();
    }

    public function createDetails(CreateHotelDetailRequest $request) {
        $response = $this->hotelDetailService->create($request->all());

        return response()->json($response);
    }
    public function updateDetails(CreateHotelDetailRequest $request) {
        $response = $this->hotelDetailService->update($request->all());

        return response()->json($response);
    }
}
