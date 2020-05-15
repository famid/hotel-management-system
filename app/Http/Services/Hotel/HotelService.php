<?php


namespace App\Http\Services\Hotel;


use App\Http\Repository\HotelRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

class HotelService
{
    protected $hotelRepository;
    protected $errorResponse;
    protected $errorMessage;

    /**
     * HotelService constructor.
     */
    public function __construct() {
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
     * @param array $data
     * @return array
     */
    public function create (array $data) :array{
        $id =Auth::id();
        try {
            $data = [
                'name' => $data['name'],
                'star_rating' => $data['star_rating'],
                'user_id' => $id
            ];
            $createHotelResponse = $this->hotelRepository->create($data);
           if(!$createHotelResponse) {

               return $this->errorResponse;
           }

            return ['success' => true , 'message' => 'Hotel was created successfully'];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function update (array $data) :array{
        $id =Auth::id();
        try {
            $where = ['id' => $data['id']];
            $data = [
                'name' => $data['name'],
                'star_rating' => $data['star_rating'],
                'user_id' => $id
            ];
            $updateHotelResponse = $this->hotelRepository->update($where,$data);
            if(!$updateHotelResponse) {

                return $this->errorResponse;
            }

            return ['success' => true , 'message' => 'Hotel was updated successfully'];
        } catch (Exception $e) {

            return $this->errorResponse;
        }
    }

    /**
     * @param int $id
     * @return array
     */
    public function delete(int $id):array {
        $where = ['id' => $id];
        $deleteHotelResponse = $this->hotelRepository->deleteWhere($where);
        if(!$deleteHotelResponse) {

            return $this->errorResponse;
        }

        return ['success' => true , 'message' => 'Hotel was deleted successfully'];
    }
}
