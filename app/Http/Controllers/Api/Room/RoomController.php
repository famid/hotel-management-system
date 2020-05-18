<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Room\CreateRoomRequest;
use App\Http\Requests\Api\Room\DeleteRoomRequest;
use App\Http\Requests\Api\Room\UpdateRoomImagesRequest;
use App\Http\Requests\Api\Room\UpdateRoomRequest;
use App\Http\Services\Room\RoomService;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    protected $roomService;

    /**
     * RoomController constructor.
     */
    public function __construct() {
        $this->roomService = new RoomService();
    }

    /**
     * @param CreateRoomRequest $request
     * @return JsonResponse
     */
    public function createRoom (CreateRoomRequest $request) {
        $response = $this->roomService->create($request->all());

        return response()->json($response);
    }

    /**
     * @param UpdateRoomRequest $request
     * @return JsonResponse
     */
    public function updateRoom (UpdateRoomRequest $request) {
        $response = $this->roomService->update($request->all());

        return response()->json($response);
    }

    /**
     * @param DeleteRoomRequest $request
     * @return JsonResponse
     */
    public function deleteRoom (DeleteRoomRequest $request) {
        $response = $this->roomService->delete($request->id);

        return response()->json($response);
    }

    /**
     * @return JsonResponse
     */
    public function getAllRoom () {
        $response = $this->roomService->allRoom();

        return response()->json($response);

    }

    /**
     * @param DeleteRoomRequest $request
     * @return JsonResponse
     */
    public function roomDetails (DeleteRoomRequest $request) {
        $response = $this->roomService->getRoomDetails($request->id);

        return response()->json($response);

    }

    /**
     * @param DeleteRoomRequest $request
     * @return JsonResponse
     */
    public function deleteRoomImage (DeleteRoomRequest $request) {
        $response = $this->roomService->deleteRoomImage($request->id);

        return response()->json($response);

    }

    /**
     * @param UpdateRoomImagesRequest $request
     * @return JsonResponse
     */
    public function updateRoomImages (UpdateRoomImagesRequest $request) {
        $response = $this->roomService->updateRoomImages($request->all());

        return response()->json($response);

    }

}
