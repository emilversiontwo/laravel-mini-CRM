<?php

namespace App\Http\Requests\Ticket;

use App\Rules\ValidatePhone;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "StoreTicketRequest",
    required: ["name", "phone", "email", "subject", "text"],
    properties: [
        new OA\Property(property: "name", type: "string", example: "John Doe"),
        new OA\Property(property: "phone", type: "string", example: "+7 912 345 67 89"),
        new OA\Property(property: "email", type: "string", format: "email", example: "Johndoe@example.com"),
        new OA\Property(property: "subject", type: "string", example: "Problem"),
        new OA\Property(property: "text", type: "string", example: "Some description about the ticket"),

        new OA\Property(
            property: "files[]",
            description: "Array of uploaded files (multiple)",
            type: "array",
            items: new OA\Items(type: "string", format: "binary")
        ),
    ],
    type: "object"
)]
class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
            ],
            'phone' => [
                'required',
                'string',
                new ValidatePhone(),
            ],
            'email' => [
                'required',
                'email',
            ],
            'subject' => [
                'required',
                'string',
            ],
            'text' => [
                'required',
                'string',
            ],
            'files' => [
                'sometimes',
                'array',
            ],
            'files.*' => [
                'file',
                'max:5120',
            ]
        ];
    }
}
