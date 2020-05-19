<?php


namespace App\Http\Services\Room;


use App\Http\Repository\RoomImageRepository;
use App\Http\Repository\RoomRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class RoomService
{
    protected $roomRepository;
    protected $roomImageRepository;
    protected $errorResponse;
    protected $errorMessage;

    /**
     * RoomService constructor.
     */
    public function __construct() {
        $this->roomRepository = new RoomRepository();
        $this->roomImageRepository = new RoomImageRepository();
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
            DB::beginTransaction();
            $createRoomResponse = $this->createRoom($data);
            if (!$createRoomResponse['success']) {
                throw new Exception('Failed');
            }
            $roomId = $createRoomResponse['room_id'];
            $roomImages = $data['picture'];
            $storeRoomPictureResponse = $this->storeRoomPictures($roomImages, $roomId);
            if (!$storeRoomPictureResponse) {
                throw new Exception('Failed');
            }
            DB::commit();

            return ['success' => true, 'message' => __('room is created successfully')];
        } catch (Exception $e) {
            DB::rollBack();

            return $this->errorResponse;
        }
    }

    /**
     * @param $data
     * @return array
     */
    protected function createRoom($data) :array{
        $availableAt = Carbon::now();
        $roomData = $this->prepareRoomData($data, $availableAt);
        $createRoom = $this->roomRepository->create($roomData);
        if (!$createRoom) {

            return $this->errorResponse;
        }

        return ['success' => true, 'room_id' => $createRoom->id, 'message' => __('room is created is created successfully')];
    }

    /**
     * @param array $data
     * @param $availableAt
     * @return array
     */
    private function prepareRoomData(array $data, $availableAt): array {
        return [
            'room_number' => $data['room_number'],
            'room_type' => $data['room_type'],
            'floor_no' => $data['floor_no'],
            'rent' => $data['rent'],
            'smoking_zone' => false,
            'hotel_id' => $data['hotel_id'],
            'reservation_status' => true,
            'available_at' => $availableAt
        ];
    }

    /**
     * @param $roomImages
     * @param $roomId
     * @return bool
     */
    protected function storeRoomPictures($roomImages, $roomId) {
        $roomImageData = $this->uploadRoomImages($roomImages, $roomId);
        if (empty($roomImageData)) {

            return false;
        }
        foreach ($roomImageData as $key => $image) {
            $createRoomImageResponse = $this->roomImageRepository->create($image);
            if (!$createRoomImageResponse) {

                return false;
            }
        }

        return true;
    }

    /**
     * @param $roomImages
     * @param $roomId
     * @return array
     */
    protected function uploadRoomImages($roomImages, $roomId) :array{
        $imageData = [];
        foreach ($roomImages as $key => $value) {
            $image = uploadFile($value, imagePath());
            $data = [
                'room_id' => $roomId,
                'picture' => $image
            ];
            array_push($imageData, $data);
        }

        return $imageData;
    }

    /**
     * @param array $data
     * @return array
     */
    public function update(array $data): array {
        try {
            $where = ['id' => $data['id']];
            $availableAt = Carbon::now();
            $roomData = $this->prepareRoomData($data, $availableAt);
            $updateRoomResponse = $this->roomRepository->update($where, $roomData);
            if (!$updateRoomResponse) {

                return $this->errorResponse;
            }

            return ['success' => true, 'message' => __('Room is updated successfully')];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param int $id
     * @return array
     */
    public function delete(int $id): array {
        try {
            $where = ['id' => $id];
            $deleteRoomResponse = $this->roomRepository->deleteWhere($where);
            if (!$deleteRoomResponse) {

                return $this->errorResponse;
            }

            return ['success' => true, 'message' => __('Room is deleted successfully')];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @return array
     */
    public function allRoom(): array {
        try {
            $allRoomData = $this->roomRepository->getAll();
            if ($allRoomData->isEmpty()) {

                return $this->errorResponse;
            }

            return ['success' => true, 'data' => $allRoomData, 'message' => __('All Room are found successfully')];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param int $id
     * @return array
     */
    public function getRoomDetails(int $id): array {
        try {
            $where = ['id' => $id];
            $roomDetails['details'] = $this->roomRepository->getWhere($where);
            if ($roomDetails['details']->isEmpty()) {

                return $this->errorResponse;
            }
            $roomDetails['images'] = $this->showAllPictureByRoomId($id);
            if ($roomDetails['images']->isEmpty()) {

                return $this->errorResponse;
            }

            return ['success' => true, 'data' => $roomDetails, 'message' => __('Hotel Details is found successfully')];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param int $roomId
     * @return mixed
     */
     protected  function  showAllPictureByRoomId (int $roomId) {
         $where = ['room_id' => $roomId];
         $roomImages = $this->roomImageRepository->getWhere($where);
         $i=0;
         foreach ($roomImages as $value){
             $roomImages[$i++] = asset(imagePath() . '/' . $value->picture);
         }

         return $roomImages;
     }

    /**
     * @param int $imageId
     * @return array
     */
     public function deleteRoomImage (int $imageId) :array{
         try {
             $imageFileName = $this->roomImageRepository->getImageName($imageId);
             $where = ['id' => $imageId];
             DB::beginTransaction();
             $deleteImageResponse = $this->roomImageRepository->deleteWhere($where);
             if (!$deleteImageResponse) {
                 DB::rollBack();

                 return $this->errorResponse;
             }
             $deleteImageFile = deleteFile(imagePath(), $imageFileName);
             if (!$deleteImageFile) {
                 DB::rollBack();

                 return $this->errorResponse;
             }
             DB::commit();

             return ['success' => true, 'message' => __('Room Picture is deleted successfully')];
         } catch (Exception $e) {
            DB::rollBack();

             return $this->errorResponse;
         }
     }

    /**
     * @param array $data
     * @return array
     */
    public function updateRoomImages(array $data) :array{
         try {
             $roomId = $data ['room_id'];
             $roomImages = $data['picture'];
             $where = ['room_id' => $roomId];
             DB::beginTransaction();
             $deleteImageResponse = $this->roomImageRepository->deleteWhere($where);
             if (!$deleteImageResponse) {
                 DB::rollBack();

                 return $this->errorResponse;
             }
             $deleteImageFile = $this->deleteImageFileByRoomId($roomId);
             if (!$deleteImageFile) {
                 DB::rollBack();

                 return $this->errorResponse;
             }
             $storeRoomPictureResponse = $this->storeRoomPictures($roomImages,$roomId);
             if(!$storeRoomPictureResponse) {
                 DB::rollBack();

                 return $this->errorResponse;
             }
            DB::commit();

             return ['success' => true, 'message' => __('Pictures are Updated successfully')];
         } catch (Exception $e) {
             DB::rollBack();

             return $this->errorResponse;
         }
     }

    /**
     * @param int $roomId
     * @return bool
     */
    private function deleteImageFileByRoomId(int $roomId) :bool{
         $imageFileName = $this->roomImageRepository->getImageNameByRoomId($roomId);
         foreach ($imageFileName as $fileName) {
             $deleteImageFile = deleteFile(imagePath(), $fileName);
             if (!$deleteImageFile) {

                 return false;
             }
         }

         return true;
     }

    /**
     * @param array $data
     * @return array
     */
    public function filter (array $data) :array {
        try {
            $filterData = $this->prepareFilterData($data);
            $filterResponse = $this->roomRepository->filter($filterData['where'],$filterData['priceRange']);

            if ($filterResponse->isEmpty()) {

                return ['success' => false, 'message' => __('No Result Found')];
            }

            return ['success' => true, 'data' => $filterResponse, 'message' => __('Filter Result found successfully')];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param $data
     * @return array[]
     */
    protected  function prepareFilterData(array $data) :array{
        $priceRange = [
                $minPriceRange = (!is_null($data['price_range'][0])) ? $data['price_range'][0] : $this->roomRepository->minPriceRange(),
                $maxPriceRange =(!is_null($data['price_range'][1])) ? $data['price_range'][1] : $this->roomRepository->maxPriceRange()
            ];
            $where = ['rooms.reservation_status' => ACTIVE_STATUS];
            if($data['room_type'])   {$where['rooms.room_type'] = $data['room_type'];}

        return ['where' => $where , 'priceRange' => $priceRange];
    }
}
