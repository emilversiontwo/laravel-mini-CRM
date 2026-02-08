<?php

namespace App\Http\Resources\Ticket;

use App\Http\Resources\Customer\CustomerResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use OpenApi\Attributes as OA;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @mixin Ticket
 */
#[OA\Schema(
    schema: "TicketResource",
    title: "TicketResource",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 123),
        new OA\Property(property: "subject", type: "string", example: "Problem"),
        new OA\Property(property: "text", type: "string", example: "Some description about the ticket"),
        new OA\Property(property: "status", type: "string", example: "new"),
        new OA\Property(property: "manager_responded", type: "string", format: 'date-time', example: '2024-01-01T00:00:00Z'),

        new OA\Property(
            property: "files",
            description: "Array of file URLs",
            type: "array",
            items: new OA\Items(type: "string", format: "uri", example: "http://localhost/storage/4/conversions/1_thumb.png")
        ),

        new OA\Property(property: "customer", ref: CustomerResource::class),
        new OA\Property(property: "crated_at", type: "string", format: 'date-time', example: '2024-01-01T00:00:00Z'),
        new OA\Property(property: "updated_at", type: "string", format: 'date-time', example: '2024-01-01T00:00:00Z'),
    ],
    type: "object"
)]
class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'text' => $this->text,
            'status' => $this->status,
            'manager_responded' => $this->manager_responded,
            'files' => $this->getMedia('*')->map(function (Media $media) {
                return $media->getUrl();
            }),
            'customer' => new CustomerResource($this->customer),
            'crated_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
