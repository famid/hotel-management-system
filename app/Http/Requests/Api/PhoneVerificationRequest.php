<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class PhoneVerificationRequest extends FormRequest
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
    public function rules()
    {
        return [
            'phone_verification_code' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'phone_verification_code.required' => __('Code field can not be empty'),
//            'phone_verification_code.numeric' => __('Code field must be numeric'),
        ];
    }

    /**
     * @param Validator $validator
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator)
    {

        if ($this->header('accept') == "application/json") {
            $errors = '';
            if ($validator->fails()) {
                $e = $validator->errors()->all();
                dd($e);
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
