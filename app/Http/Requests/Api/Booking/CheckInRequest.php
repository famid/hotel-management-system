<?php

namespace App\Http\Requests\Api\Booking;

use App\Rules\PaymentRules;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CheckInRequest extends FormRequest
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
            'room_id' => ['required',new PaymentRules()],
            'paid_amount' => 'required|min:MINIMUM_PAID_AMOUNT',
            'duration_of_stay' => 'required|integer',
            'check_in' => 'required',
        ];
    }

    public function messages() {

        return [
            'room_id.required' => __('room_id field can not be empty'),
            'paid_amount.required' => __('paid_amount field can not be empty'),
            'duration_of_stay.required' => __('duration_of_stay field can not be empty'),
            'duration_of_stay.integer' => __('duration_of_stay field must be integer'),
            'check_in.required' => __('check_in field can not be empty')
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
