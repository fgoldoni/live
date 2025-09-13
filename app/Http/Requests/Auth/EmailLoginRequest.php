<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class EmailLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email:rfc,dns'],
            'password' => ['required', 'string', 'min:8'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => __('validation.required'),
            'email.email'       => __('validation.email'),
            'password.required' => __('validation.required'),
            'password.min'      => __('validation.min.string'),
            'remember.boolean'  => __('validation.boolean'),
        ];
    }

    public function attributes(): array
    {
        return [
            'email'    => __('Email address'),
            'password' => __('Password'),
            'remember' => __('Remember me'),
        ];
    }
}
