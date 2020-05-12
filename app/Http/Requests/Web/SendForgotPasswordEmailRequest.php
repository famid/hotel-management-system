<?php

namespace App\Http\Requests\Web;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SendForgotPasswordEmailRequest extends FormRequest
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
            'email' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => __('Email can not be empty')
        ];
    }

    /**
     * @param Validator $validator
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
