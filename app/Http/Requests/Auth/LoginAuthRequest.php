<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "LoginAuthRequest",
    required: ["email", "password"],
    properties: [
        new OA\Property(property: "email", type: "string", format: 'email', example: "admin@admin.com"),
        new OA\Property(property: "password", type: "string", format: "password", example: "password"),
    ],
    type: "object"
)]
class LoginAuthRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
