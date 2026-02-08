<?php

namespace App\Http\Requests\Ticket;

use App\Enums\Ticket\TicketStatusEnum;
use App\Rules\ValidatePhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: "tickets_from",
    name: "from",
    in: "query",
    required: false,
    schema: new OA\Schema(type: "string", format: "date", example: '2024-01-01T00:00:00Z')
)]
#[OA\Parameter(
    parameter: "tickets_to",
    name: "to",
    in: "query",
    required: false,
    schema: new OA\Schema(type: "string", format: "date", example: '2024-01-01T00:00:00Z')
)]
#[OA\Parameter(
    parameter: "tickets_subject",
    name: "subject",
    in: "query",
    required: false,
    schema: new OA\Schema(type: "string", example: 'Some subject')
)]
#[OA\Parameter(
    parameter: "tickets_text",
    name: "text",
    in: "query",
    required: false,
    schema: new OA\Schema(type: "string", example: 'Some description about the ticket')
)]
#[OA\Parameter(
    parameter: "tickets_status",
    name: "status",
    in: "query",
    required: false,
    schema: new OA\Schema(ref: TicketStatusEnum::class)
)]
#[OA\Parameter(
    parameter: "tickets_name",
    name: "name",
    in: "query",
    required: false,
    schema: new OA\Schema(type: "string", example: 'John Doe')
)]
#[OA\Parameter(
    parameter: "tickets_email",
    name: "email",
    in: "query",
    required: false,
    schema: new OA\Schema(type: "string", format: "email", example: 'Johndoe@google.com')
)]
#[OA\Parameter(
    parameter: "tickets_phone",
    name: "phone",
    in: "query",
    required: false,
    schema: new OA\Schema(type: "string", example: '+7 912 345 56 78')
)]
class IndexTicketRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'subject' => ['nullable', 'string'],
            'text' => ['nullable', 'string'],
            'status' => ['nullable', Rule::enum(TicketStatusEnum::class)],
            'name' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', new ValidatePhone()],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
