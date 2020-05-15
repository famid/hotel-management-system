<?php

namespace App\Http\Requests\Api\Hotel;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreateHotelFeatureRequest extends FormRequest
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
            'feature' => 'required',
            'hotel_id' => 'required|integer'
        ];
    }

    /**
     * @return array
     */
    public function messages() {

        return [
            'feature.required' => __('feature field can not be empty'),
            'hotel_id.required' => __('hotel_id field can not be empty'),
            'hotel_id.integer' => __('hotel_id field should be integer')
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
