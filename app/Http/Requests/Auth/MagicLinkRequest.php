<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class MagicLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email:rfc,dns'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('validation.required'),
            'email.email'    => __('validation.email'),
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => __('Email address'),
        ];
    }
}
