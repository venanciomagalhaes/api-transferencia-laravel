<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\UserStoreRequest;
use App\Mappers\V1\UserMapper;
use App\Services\V1\UserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="Desafio XYZ Simplificado",
 *         version="1.0.0",
 *         description="Documentação da API do desafio XYZ simplificado",
 *         @OA\Contact(
 *             email="venanciomagalhaesd@gmail.com"
 *         )
 *     ),
 *     @OA\Server(
 *         url=L5_SWAGGER_CONST_HOST,
 *         description="Servidor de desenvolvimento"
 *     )
 * )
 */
class UserController extends Controller
{

    public function __construct(
        private readonly UserService $userService,
    )
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     summary="Lista todos os usuários",
     *     tags={"Usuários"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Número da página para paginação",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Users listed successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="uuid", type="string", format="uuid", example="c1d2e3f4-5678-9012-3456-789abcdef012"),
     *                     @OA\Property(property="name", type="string", example="Clark Kent"),
     *                     @OA\Property(property="role", type="string", example="merchant"),
     *                     @OA\Property(property="email", type="string", format="email", example="superman@dailyplanet.com"),
     *                     @OA\Property(property="cpf_cnpj", type="string", example="98765432000188"),
     *                     @OA\Property(
     *                         property="permissions",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="name", type="string", example="receive-a-transfer"),
     *                             @OA\Property(property="description", type="string", example="Allows the user to receive a transfer")
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="__links",
     *                         type="object",
     *                         @OA\Property(
     *                             property="self",
     *                             type="object",
     *                             @OA\Property(property="href", type="string", example="http://localhost:8989/api/v1/users/c1d2e3f4-5678-9012-3456-789abcdef012"),
     *                             @OA\Property(property="method", type="string", example="GET")
     *                         ),
     *                         @OA\Property(
     *                             property="index",
     *                             type="object",
     *                             @OA\Property(property="href", type="string", example="http://localhost:8989/api/v1/users"),
     *                             @OA\Property(property="method", type="string", example="GET")
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="pagination",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="first_page_url", type="string", example="http://localhost:8989/api/v1/users?page=1"),
     *                 @OA\Property(property="last_page", type="integer", example=1),
     *                 @OA\Property(property="last_page_url", type="string", example="http://localhost:8989/api/v1/users?page=1"),
     *                 @OA\Property(
     *                     property="links",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="url", type="string", nullable=true, example=null),
     *                         @OA\Property(property="label", type="string", example="&laquo; Previous"),
     *                         @OA\Property(property="active", type="boolean", example=false)
     *                     )
     *                 ),
     *                 @OA\Property(property="next_page_url", type="string", nullable=true, example=null),
     *                 @OA\Property(property="path", type="string", example="http://localhost:8989/api/v1/users"),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=204, description="Nenhum usuário encontrado")
     * )
     */

    public function index(): JsonResponse
    {
        $users = $this->userService->index();
        $statusCode = $users->isEmpty() ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        $response = UserMapper::toCollectionResource($users);
        return response()->json($response)->setStatusCode($statusCode);
    }


    /**
     * @OA\Post(
     *     path="/api/v1/users",
     *     summary="Cria um novo usuário",
     *     tags={"Usuários"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Bruce Wayne"),
     *             @OA\Property(property="role", type="string", example="merchant"),
     *             @OA\Property(property="email", type="string", format="email", example="batman@wayneenterprises.com"),
     *             @OA\Property(property="cpf_cnpj", type="string", example="12345678000199"),
     *             @OA\Property(property="password", type="string", format="password", example="SecretPass@123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="SecretPass@123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User created successfully."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="uuid", type="string", format="uuid", example="a9b8c7d6-e5f4-3210-9988-776655443322"),
     *                 @OA\Property(property="name", type="string", example="Bruce Wayne"),
     *                 @OA\Property(property="role", type="string", example="merchant"),
     *                 @OA\Property(property="email", type="string", format="email", example="batman@wayneenterprises.com"),
     *                 @OA\Property(property="cpf_cnpj", type="string", example="12345678000199"),
     *                 @OA\Property(
     *                     property="permissions",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="name", type="string", example="receive-a-transfer"),
     *                         @OA\Property(property="description", type="string", example="Allows the user to receive a transfer")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="__links",
     *                     type="object",
     *                     @OA\Property(
     *                         property="self",
     *                         type="object",
     *                         @OA\Property(property="href", type="string", example="http://localhost:8989/api/v1/users/a9b8c7d6-e5f4-3210-9988-776655443322"),
     *                         @OA\Property(property="method", type="string", example="GET")
     *                     ),
     *                     @OA\Property(
     *                         property="index",
     *                         type="object",
     *                         @OA\Property(property="href", type="string", example="http://localhost:8989/api/v1/users"),
     *                         @OA\Property(property="method", type="string", example="GET")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Erro de validação"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */

    public function store(UserStoreRequest $request): JsonResponse
    {
        $dto = UserMapper::toStoreDto($request);
        $user = $this->userService->store($dto);
        $response = UserMapper::toResource($user);
        return response()->json($response)->setStatusCode(Response::HTTP_CREATED);
    }


    /**
     * @OA\Get(
     *     path="/api/v1/users/{uuid}",
     *     summary="Obtém um usuário pelo UUID",
     *     tags={"Usuários"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID do usuário",
     *         @OA\Schema(type="string", format="uuid", example="a1b2c3d4-e5f6-7890-1234-56789abcdef0")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do usuário",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User retrieved successfully."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="uuid", type="string", format="uuid", example="a1b2c3d4-e5f6-7890-1234-56789abcdef0"),
     *                 @OA\Property(property="name", type="string", example="Tony Stark"),
     *                 @OA\Property(property="role", type="string", example="customer"),
     *                 @OA\Property(property="email", type="string", format="email", example="ironman@starkindustries.com"),
     *                 @OA\Property(property="cpf_cnpj", type="string", example="12345678900"),
     *                 @OA\Property(
     *                     property="permissions",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="name", type="string", example="fly-with-armor"),
     *                         @OA\Property(property="description", type="string", example="Allows the user to fly with the Iron Man suit")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="__links",
     *                     type="object",
     *                     @OA\Property(
     *                         property="self",
     *                         type="object",
     *                         @OA\Property(property="href", type="string", example="http://localhost:8989/api/v1/users/a1b2c3d4-e5f6-7890-1234-56789abcdef0"),
     *                         @OA\Property(property="method", type="string", example="GET")
     *                     ),
     *                     @OA\Property(
     *                         property="index",
     *                         type="object",
     *                         @OA\Property(property="href", type="string", example="http://localhost:8989/api/v1/users"),
     *                         @OA\Property(property="method", type="string", example="GET")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Usuário não encontrado")
     * )
     */

    public function show(string $uuid): JsonResponse
    {
        $user = $this->userService->show($uuid);
        $response = UserMapper::toDetailsResource($user);
        return response()->json($response)->setStatusCode(Response::HTTP_OK);
    }

}
