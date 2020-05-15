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
     * @param $picture
     * @return array
     */
    protected function pictureUpload ($picture) {
        try {
            //Get the file name with the extension
            $pictureNameWithExtension = $picture->getClientOriginalName();
            //Get just file name
            $pictureName = pathinfo($pictureNameWithExtension, PATHINFO_FILENAME);
            $pictureExtension = $picture->getClientOriginalExtension();
            //File name to store
            $pictureNameToStore = $pictureName.'_'.time().'_'.$pictureExtension;
            //upload file path

            return ['success' => true, 'pictureName' => $pictureNameToStore];
        } catch (Exception $e) {

            return ['success' => false, 'pictureName' => null,'message'=>'there is no picture'];
        }
    }

    /**
     * @param $data
     * @param $roomId
     * @return array
     */
    protected function roomImagesData($data, $roomId) {
        $roomImagesData = [];
        foreach ($data['picture'] as  $picture) {

            $pictureUploadResponse = $this->pictureUpload( $picture);
            if (! $pictureUploadResponse['success']) {

                return $this->errorResponse;
            }
            $picture->storeAs('public/room_images', $pictureUploadResponse['pictureName']);

            $arrayData = [
                'picture' => $pictureUploadResponse['pictureName'],
                'room_id' => $roomId
            ];

            array_push($roomImagesData, $arrayData);
        }

        return $roomImagesData;
    }

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data) :array{

        try {
            DB::beginTransaction();
            $createRoomResponse = $this->createRoom($data);
            if(!$createRoomResponse['success']) {
                throw new Exception('Failed');
            }
            $roomId = $createRoomResponse['data'];
            $storeRoomPictureResponse = $this->storeRoomPicture($data,$roomId);
            if(!$storeRoomPictureResponse['success']) {
                throw new Exception('Failed');
            }
            DB::commit();

            return ['success' => true , 'message' => 'room is created successfully'];
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse;
        }
    }

    /**
     * @param $data
     * @param $roomId
     * @return array
     */
    protected function storeRoomPicture ($data, $roomId) {
        try {
            $roomImageData = $this->roomImagesData($data,$roomId);
            if(is_null($roomImageData)) {

                return $this->errorResponse;
            }
            foreach($roomImageData as $key => $image) {
                $createRoomImageResponse = $this->roomImageRepository->create($image);
                if(!$createRoomImageResponse) {

                    return $this->errorResponse;
                }
            }
         return ['success' => true, 'message' => 'Room is created successfully and picture is stored successfully'] ;
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param array $data
     * @param $availableAt
     * @return array
     */
    private function prepareRoomData(array $data, $availableAt) : array {
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
     * @param $data
     * @return array
     */
    protected function createRoom ($data) {
        try {
            $availableAt = Carbon::now();
            $roomData = $this->prepareRoomData($data, $availableAt);
            $createRoom = $this->roomRepository->create($roomData);
            if(!$createRoom) {

                return $this->errorResponse;
            }

            return ['success' => true, 'data' => $createRoom->id,'message' => 'room is created is created successfully'];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function update(array $data) :array{
        try {
            $where = ['id' => $data['id']];
            $availableAt = Carbon::now();
            $roomData = $this->prepareRoomData($data, $availableAt);
            $updateRoomResponse = $this->roomRepository->update($where,$roomData);
            if(!$updateRoomResponse) {

                return $this->errorResponse;
            }

            return ['success' => true, 'message' => 'Room is updated succfully'];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param int $id
     * @return array
     */
    public function delete (int $id) {
        $where = ['id' => $id];
        $deleteRoomResponse = $this->roomRepository->deleteWhere($where);
        if(!$deleteRoomResponse) {

            return $this->errorResponse;
        }

        return ['success' => true , 'message' => 'Room was deleted successfully'];
    }
}