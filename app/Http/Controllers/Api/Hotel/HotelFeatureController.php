<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Hotel\CreateHotelFeatureRequest;
use App\Http\Requests\Api\Hotel\DeleteHotelFeatureRequest;
use App\Http\Requests\Api\Hotel\UpdateHotelFeatureRequest;
use App\Http\Services\Hotel\HotelFeatureService;
use Illuminate\Http\JsonResponse;

class HotelFeatureController extends Controller
{
    protected $hotelFeatureService;

    /**
     * HotelFeatureController constructor.
     */
    public function __construct() {
        $this->hotelFeatureService = new HotelFeatureService();
    }

    /**
     * @param CreateHotelFeatureRequest $request
     * @return JsonResponse
     */
    public function createFeature (CreateHotelFeatureRequest $request) {

        $response = $this->hotelFeatureService->create($request->all());

        return response()->json($response);
    }

    public function updateFeature(UpdateHotelFeatureRequest $request) {
        $response = $this->hotelFeatureService->update($request->all());

        return response()->json($response);
    }

    /**
     * @param DeleteHotelFeatureRequest $request
     * @return JsonResponse
     */
    public function deleteFeature(DeleteHotelFeatureRequest $request) {
        $response = $this->hotelFeatureService->delete($request->id);

        return response()->json($response);
    }
}
