<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class PhoneLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone_full' => ['required', 'string', 'phone:E164'],
            'password'   => ['required', 'string', 'min:8'],
            'remember'   => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone_full.required' => __('validation.required'),
            'phone_full.phone'    => __('validation.phone'),
            'password.required'   => __('validation.required'),
            'password.min'        => __('validation.min.string'),
            'remember.boolean'    => __('validation.boolean'),
        ];
    }

    public function attributes(): array
    {
        return [
            'phone_full' => __('Phone number'),
            'password'   => __('Password'),
            'remember'   => __('Remember me'),
        ];
    }
}
