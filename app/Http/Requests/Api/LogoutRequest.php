<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class LogoutRequest extends FormRequest
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
            'device_type' => 'required|in:' . implode(",", ['android', 'ios']),
            'device_token' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'device_type.required' => __('Device type is required'),
            'device_type.in' => __('Device type is invalid'),
            'device_token.required' => __('Device token is required'),
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
