<?php

namespace App\Http\Controllers\Api\v1\Ticket;

use App\Exceptions\AppLogicException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\IndexTicketRequest;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use App\Http\Resources\Ticket\TicketResource;
use App\Models\Ticket;
use App\Services\Customer\Dto\FindOrStoreCustomerDto;
use App\Services\Customer\Http\CustomerService;
use App\Services\Ticket\Dto\IndexTicketDto;
use App\Services\Ticket\Dto\StoreTicketDto;
use App\Services\Ticket\Dto\UpdateTicketDto;
use App\Services\Ticket\Http\TicketService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

#[OA\Info(
    version: "1.0.0",
    description: "API documentation for Ticket API",
    title: "Ticket API",
    contact: new OA\Contact(
        name: "API Support",
        url: "https://github.com/emilversiontwo/laravel-mini-CRM",
        email: "emilusupov08576@gmail.com"
    )
)]
class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService   $ticketService,
        private readonly CustomerService $customerService,
    )
    {
    }

    /**
     * Display a listing of the resource.
     * @param IndexTicketRequest $request
     * @return JsonResponse
     */
    #[OA\Get(
        path: "/api/tickets/statistics",
        description: "Get tickets statistics",
        summary: "Get Tickets",
        tags: ["Tickets"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/tickets_from"),
            new OA\Parameter(ref: "#/components/parameters/tickets_to"),
            new OA\Parameter(ref: "#/components/parameters/tickets_subject"),
            new OA\Parameter(ref: "#/components/parameters/tickets_text"),
            new OA\Parameter(ref: "#/components/parameters/tickets_status"),
            new OA\Parameter(ref: "#/components/parameters/tickets_name"),
            new OA\Parameter(ref: "#/components/parameters/tickets_email"),
            new OA\Parameter(ref: "#/components/parameters/tickets_phone"),
            new OA\Parameter(
                name: "page",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", example: '1')
            ),
            new OA\Parameter(
                name: "per_page",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", example: '10')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Ticket get successfully",
                content: new OA\JsonContent(ref: TicketResource::class),
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            )
        ]
    )]
    public function index(IndexTicketRequest $request): JsonResponse
    {
        $data = $request->validated();

        $dto = new IndexTicketDto($data);
        $tickets = $this->ticketService->index($dto);

        return TicketResource::collection($tickets)->response()->setStatusCode(ResponseCode::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreTicketRequest $request
     * @return JsonResponse
     * @throws AppLogicException
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    #[OA\Post(
        path: "/api/tickets",
        description: "Creates a new Ticket and returns the created Ticket object",
        summary: "Create a new Ticket",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(ref: StoreTicketRequest::class),
                encoding: [
                    new OA\Encoding(property: "files", style: "form", explode: true)
                ]
            )
        ),
        tags: ["Tickets"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Ticket created successfully",
                content: new OA\JsonContent(ref: TicketResource::class),
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            )
        ]
    )]
    public function store(StoreTicketRequest $request): JsonResponse
    {
        $data = $request->validated();

        $dto = new FindOrStoreCustomerDto($data);

        $customer = $this->customerService->findOrStore($dto);

        $dto = new StoreTicketDto([
            ...$data,
            'customer' => $customer
        ]);

        $ticket = $this->ticketService->store($dto);

        return new TicketResource($ticket)->response()->setStatusCode(ResponseCode::HTTP_CREATED);
    }

    /**
     * @param Ticket $ticket
     * @param UpdateTicketRequest $request
     * @return JsonResponse
     */
    #[OA\Put(
        path: "/api/tickets/{ticketId}",
        description: "Update the Ticket status and returns the Ticket object",
        summary: "Update the Ticket",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(ref: UpdateTicketRequest::class),
            )
        ),
        tags: ["Tickets"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Ticket updated successfully",
                content: new OA\JsonContent(ref: TicketResource::class),
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            )
        ]
    )]
    public function update(Ticket $ticket, UpdateTicketRequest $request): JsonResponse
    {
        $data = $request->validated();

        $dto = new UpdateTicketDto([
            ...$data,
            'ticket' => $ticket
        ]);

        $ticket = $this->ticketService->update($dto);

        return new TicketResource($ticket)->response()->setStatusCode(ResponseCode::HTTP_OK);
    }
}
