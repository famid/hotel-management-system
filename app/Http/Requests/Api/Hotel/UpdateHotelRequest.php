<?php

namespace App\Http\Requests\Api\Hotel;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UpdateHotelRequest extends FormRequest
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
     * @return string[]
     */
    public function rules() {
        return [
            'id' =>'required|integer',
            'name' => 'required|string',
            'star_rating' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages() {

        return [
            'id.required' => __('name field can not be empty'),
            'id.integer' => __('id field must be integer'),
            'name.required' => __('name field can not be empty'),
            'name.string' => __('name field must be string'),
            'star_rating.required' => __('star_rating field can not be empty')
        ];
    }

    /**
     * @param Validator $validator
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator){
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
