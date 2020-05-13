<?php

namespace App\Http\Requests\Api\Hotel;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UpdateHotelDetailRequest extends FormRequest
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
            'id' => 'required|integer',
            'hotel_id' => 'required|integer',
            'country' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'location' => 'required|string',
            'zip_code' => 'required|string'
        ];
    }

    public function messages() {

        return [
            'id.required' => __('id field can not be empty'),
            'id.integer' => __('id field must be integer'),
            'hotel_id.required' => __('hotel_id field can not be empty'),
            'hotel_id.integer' => __('hotel_id field must be integer'),
            'country.required' => __('country field can not be empty'),
            'country.string' => __('country field must be string'),
            'city.required' => __('city field can not be empty'),
            'city.string' => __('city field must be string'),
            'state.required' => __('state field can not be empty'),
            'state.string' => __('state field must be string'),
            'location.required' => __('location field can not be empty'),
            'location.string' => __('location field must be string'),
            'zip_code.required' => __('zip_code field can not be empty'),
            'zip_code.integer' => __('zip_code field must be integer')
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
