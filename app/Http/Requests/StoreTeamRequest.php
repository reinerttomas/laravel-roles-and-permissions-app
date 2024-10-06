<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

final class StoreTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'clinic_name' => ['required'],
            'user_id' => ['nullable', 'required_without_all:name,email,password', 'exists:users,id'],
            'name' => ['nullable', 'required_without:user_id', 'required_with:email,password', 'string'],
            'email' => ['nullable', 'required_without:user_id', 'required_with:name,password', 'email'],
            'password' => ['nullable', 'required_without:user_id', 'required_with:name,email', 'string', Password::defaults()],
        ];
    }
}
