<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UpdatePasswordRequest extends FormRequest
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
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'old_password.required' => __('Old password can not be empty'),
            'new_password.required' => __('New password can not be empty'),
            'new_password.min' => __('New password must be al least 8 characters'),
            'confirm_password.required' => __('Confirm password can not be empty'),
            'confirm_password.same' => __('New password and confirm password are not same')
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

            throw (new ValidationException($validator, $response))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        } else {
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }
    }
}
