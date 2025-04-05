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
 *
 *     @OA\Info(
 *         title="Desafio XYZ Simplificado",
 *         version="1.0.0",
 *         description="Documentação da API do desafio XYZ simplificado",
 *
 *         @OA\Contact(
 *             email="venanciomagalhaesd@gmail.com"
 *         )
 *     ),
 *
 *     @OA\Server(
 *         url="http://localhost:8989",
 *         description="Servidor de desenvolvimento"
 *     )
 * )
 */
class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     summary="Listar usuários",
     *     description="Retorna a lista de usuários com suas permissões, saldo da carteira e links. As únicas opções de role são: 'customer' e 'merchant'.",
     *     operationId="listUsers",
     *     tags={"Users"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Users listed successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Users listed successfully"),
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(
     *
     *                     @OA\Property(property="uuid", type="string", format="uuid", example="7487ca27-4302-4871-8ad6-52b185c8ea33"),
     *                     @OA\Property(property="name", type="string", example="Pessoa Física"),
     *                     @OA\Property(property="email", type="string", example="pf@gmail.com"),
     *                     @OA\Property(property="role", type="string", example="customer", enum={"customer", "merchant"}),
     *                     @OA\Property(property="cpf_cnpj", type="string", example="12345678909"),
     *                     @OA\Property(property="permissions", type="array",
     *
     *                         @OA\Items(type="object",
     *
     *                             @OA\Property(property="name", type="string", example="make-a-transfer"),
     *                             @OA\Property(property="description", type="string", example="Allows the user to make a transfer")
     *                         )
     *                     ),
     *                     @OA\Property(property="wallet_amount", type="number", format="float", example=956.8),
     *                     @OA\Property(property="__links", type="object",
     *                         @OA\Property(property="self", type="object",
     *                             @OA\Property(property="href", type="string", example="http://localhost:8989/api/v1/users/7487ca27-4302-4871-8ad6-52b185c8ea33"),
     *                             @OA\Property(property="method", type="string", example="GET")
     *                         ),
     *                         @OA\Property(property="index", type="object",
     *                             @OA\Property(property="href", type="string", example="http://localhost:8989/api/v1/users"),
     *                             @OA\Property(property="method", type="string", example="GET")
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="pagination", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="first_page_url", type="string", example="http://localhost:8989/api/v1/users?page=1"),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=1),
     *                 @OA\Property(property="last_page_url", type="string", example="http://localhost:8989/api/v1/users?page=1"),
     *                 @OA\Property(property="next_page_url", type="string", nullable=true, example=null),
     *                 @OA\Property(property="path", type="string", example="http://localhost:8989/api/v1/users"),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="prev_page_url", type="string", nullable=true, example=null),
     *                 @OA\Property(property="to", type="integer", example=3),
     *                 @OA\Property(property="total", type="integer", example=3),
     *                 @OA\Property(property="links", type="array",
     *
     *                     @OA\Items(type="object",
     *
     *                         @OA\Property(property="url", type="string", nullable=true, example=null),
     *                         @OA\Property(property="label", type="string", example="&laquo; Previous"),
     *                         @OA\Property(property="active", type="boolean", example=false)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="No users found"
     *     )
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
     *     summary="Criar um novo usuário",
     *     description="Cria um novo usuário CUSTOMER ou MERCHANT, inicializando sua carteira e permissões. Por padrão, cada usuário começa com R$ 1.000,00 (apenas para fins didáticos para simular a transferência sem muitos passos anteriores)",
     *     operationId="createUser",
     *     tags={"Users"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name", "email", "cpf_cnpj", "password", "password_confirmation", "role"},
     *
     *             @OA\Property(property="name", type="string", example="João da Silva"),
     *             @OA\Property(property="email", type="string", example="joao.silva@example.com"),
     *             @OA\Property(property="cpf_cnpj", type="string", example="98765432100"),
     *             @OA\Property(property="password", type="string", format="password", example="SenhaForte123!"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="SenhaForte123!"),
     *             @OA\Property(property="role", type="string", enum={"customer", "merchant"}, example="merchant")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Usuário criado com sucesso."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="uuid", type="string", format="uuid", example="d4e12e3a-74e4-4fd1-8c7f-4cbf9e2d9a92"),
     *                 @OA\Property(property="name", type="string", example="João da Silva"),
     *                 @OA\Property(property="role", type="string", example="merchant"),
     *                 @OA\Property(property="email", type="string", example="joao.silva@example.com"),
     *                 @OA\Property(property="cpf_cnpj", type="string", example="98765432100"),
     *                 @OA\Property(property="permissions", type="array",
     *
     *                     @OA\Items(type="object",
     *
     *                         @OA\Property(property="name", type="string", example="receive-transfer"),
     *                         @OA\Property(property="description", type="string", example="Allows the user to receive transfers")
     *                     )
     *                 ),
     *                 @OA\Property(property="wallet_amount", type="number", format="float", example=1000),
     *                 @OA\Property(property="__links", type="object",
     *                     @OA\Property(property="self", type="object",
     *                         @OA\Property(property="href", type="string", example="http://localhost:8989/api/v1/users/d4e12e3a-74e4-4fd1-8c7f-4cbf9e2d9a92"),
     *                         @OA\Property(property="method", type="string", example="GET")
     *                     ),
     *                     @OA\Property(property="index", type="object",
     *                         @OA\Property(property="href", type="string", example="http://localhost:8989/api/v1/users"),
     *                         @OA\Property(property="method", type="string", example="GET")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação (campos inválidos ou ausentes)"
     *     )
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
     *     summary="Buscar usuário pelo UUID",
     *     description="Retorna os detalhes de um usuário com suas permissões, saldo da carteira e links",
     *     operationId="getUserByUuid",
     *     tags={"Users"},
     *
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID do usuário",
     *
     *         @OA\Schema(type="string", format="uuid", example="7487ca27-4302-4871-8ad6-52b185c8ea33")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="User retrieved successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="uuid", type="string", format="uuid", example="7487ca27-4302-4871-8ad6-52b185c8ea33"),
     *                 @OA\Property(property="name", type="string", example="Pessoa Física"),
     *                 @OA\Property(property="role", type="string", example="customer"),
     *                 @OA\Property(property="email", type="string", example="pf@gmail.com"),
     *                 @OA\Property(property="cpf_cnpj", type="string", example="12345678909"),
     *                 @OA\Property(property="permissions", type="array",
     *
     *                     @OA\Items(type="object",
     *
     *                         @OA\Property(property="name", type="string", example="make-a-transfer"),
     *                         @OA\Property(property="description", type="string", example="Allows the user to make a transfer")
     *                     )
     *                 ),
     *                 @OA\Property(property="wallet_amount", type="number", format="float", example=956.8),
     *                 @OA\Property(property="__links", type="object",
     *                     @OA\Property(property="self", type="object",
     *                         @OA\Property(property="href", type="string", example="http://localhost:8989/api/v1/users/7487ca27-4302-4871-8ad6-52b185c8ea33"),
     *                         @OA\Property(property="method", type="string", example="GET")
     *                     ),
     *                     @OA\Property(property="index", type="object",
     *                         @OA\Property(property="href", type="string", example="http://localhost:8989/api/v1/users"),
     *                         @OA\Property(property="method", type="string", example="GET")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     * )
     */
    public function show(string $uuid): JsonResponse
    {
        $user = $this->userService->show($uuid);
        $response = UserMapper::toDetailsResource($user);

        return response()->json($response)->setStatusCode(Response::HTTP_OK);
    }
}
