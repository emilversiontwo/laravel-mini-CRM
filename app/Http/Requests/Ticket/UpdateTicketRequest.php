<?php

namespace App\Http\Requests\Ticket;

use App\Enums\Ticket\TicketStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UpdateTicketRequest",
    required: ["status"],
    properties: [
        new OA\Property(property: "status", ref: TicketStatusEnum::class),
    ],
    type: "object"
)]
class UpdateTicketRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(TicketStatusEnum::class)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
