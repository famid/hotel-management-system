<?php

namespace App\Http\Requests\Api\Room;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreateRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {

        return [
            'room_number' => 'required|integer',
            'room_type' => 'required',
            'rent' => 'required',
            'floor_no' => 'required',
            'smoking_zone' => 'required|boolean',
            'picture' => 'required',
            'hotel_id' => 'required|integer'
        ];
    }

    /**
     * @return array
     */
    public function messages() {

        return [
            'room_number.required' => __('room_number field can not be empty'),
            'room_number.integer' => __('room_number field must be integer'),
            'hotel_id.required' => __('hotel_id field can not be empty'),
            'hotel_id.integer' => __('hotel_id must be an integer'),
            'room_type.required' => __('room_type field can not be empty'),
            'rent.required' => __('rent field can not be empty'),
            'floor_no.required' => __('floor_no field can not be empty'),
            'smoking_zone.required' => __('smoking_zone field can not be empty'),
            'picture.required' => __('picture field can not be be empty'),
            'picture.image' => __('upload file should be an image'),
            'picture.max' => __('upload file can not over 2048px ')
        ];
    }
    /**
     * @param Validator $validator
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator) {
        if ($this->header('accept') == "application/json") {
            $errors = '';
            if ($validator->fails()) {
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors .= $error . "\n";
                }
            }
            $json = [
                'success' => false,
                'message' => $errors,
                'data' => null
            ];
            $response = new JsonResponse($json, 422);

            throw (new ValidationException($validator, $response))->errorBag($this->errorBag)->redirectTo($this->getRedirectUrl());
        } else {
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }
    }
}
