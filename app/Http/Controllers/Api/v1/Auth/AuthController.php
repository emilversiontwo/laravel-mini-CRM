<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Exceptions\AppLogicException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginAuthRequest;
use App\Services\Auth\Dto\LoginAuthDto;
use App\Services\Auth\Dto\LogoutAuthDto;
use App\Services\Auth\Http\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class AuthController extends Controller
{
    public function __construct(
        protected readonly AuthService $authService
    )
    {
    }

    /**
     * @param LoginAuthRequest $request
     * @return JsonResponse
     * @throws AppLogicException
     */
    #[OA\Post(
        path: "/api/login",
        description: "Creates a new Bearer Token",
        summary: "Create a new Token",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(ref: LoginAuthRequest::class),
            )
        ),
        tags: ["Auth"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Token created successfully",
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: "type", type: "string", example: "Bearer"),
                    new OA\Property(property: "token", type: "string", example: "1|dfg43g45gfdg3rg5")
                ]),
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            )
        ]
    )]
    public function login(LoginAuthRequest $request): JsonResponse
    {
        $data = $request->validated();

        $dto = new LoginAuthDto($data);
        $token = $this->authService->login($dto);

        return response()->json([
            'type' => 'Bearer',
            'token' => $token,
        ])->setStatusCode(ResponseCode::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return Response
     */

    #[OA\Post(
        path: "/api/logout",
        summary: "Delete Token",
        security: [["authApiKey" => []]],
        tags: ["Auth"],
        parameters: [
            new OA\Parameter(name: "Authorization", in: "header", schema: new OA\Schema(type:"string"))
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "No content",
                headers: [
                    new OA\Header(
                        header: "authorization",
                        schema: new OA\Schema(type: "string", example: "Bearer"),
                    ),
                ],
                content: new OA\MediaType(
                    mediaType: "application/json",
                ),
            ),
            new OA\Response(response: 401, description: "Unauthorized"),
        ],
    )]
    public function logout(Request $request): Response
    {
        $user = $request->user();

        $dto = new LogoutAuthDto(['user' => $user]);
        $this->authService->logout($dto);

        return response()->noContent()->setStatusCode(ResponseCode::HTTP_NO_CONTENT);
    }
}
