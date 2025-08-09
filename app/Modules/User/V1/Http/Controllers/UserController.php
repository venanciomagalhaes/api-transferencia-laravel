<?php

namespace App\Modules\User\V1\Http\Controllers;

use App\Modules\Common\V1\Http\Controllers\Controller;
use App\Modules\User\V1\Actions\UserStoreAction;
use App\Modules\User\V1\Http\Mappers\UserStoreMapper;
use App\Modules\User\V1\Http\Requests\UserStoreRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Api transferência simplificada",
 *     description="API que simula um sistema simplificado de transferências",
 *     @OA\Contact(
 *         email="venanciomagalhaesd@gmail.com",
 *         name="D.Venancio"
 *     )
 * )
 */
class UserController extends Controller
{
    public function __construct(
        private readonly UserStoreMapper $userStoreMapper,
        private readonly UserStoreAction $userStoreAction
    )
    {
    }


    /**
     *
     *
     * @OA\Post(
     *     path="/api/v1/auth/user",
     *     summary="Criação de um novo usuário",
     *     tags={"User"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "document", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="document", type="string", example="41590444094", description="Valid CPF or CNPJ (numbers only)"),
     *             @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Senha123!"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="Senha123!")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User created successfully."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="uuid", type="string", format="uuid", example="0af3db3a-3cfc-447b-95c1-1c1466e94c71"),
     *                 @OA\Property(property="name", type="string", example="Deividson Venancio Magalhaes"),
     *                 @OA\Property(property="type", type="string", example="common"),
     *                 @OA\Property(property="document", type="string", example="02215725621"),
     *                 @OA\Property(property="email", type="string", example="venanciomagalhsaesd@gmail.com"),
     *                 @OA\Property(property="amount", type="string", example="1000.00"),
     *                 @OA\Property(
     *                     property="permissions",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="name", type="string", example="send_transaction"),
     *                         @OA\Property(property="description", type="string", nullable=true, example="Permission to send transactions")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=409,
     *         description="User already exists (duplicate email or document) or Invalid document",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This user already exists")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 description="Field validation errors",
     *                 example={
     *                     "email": {"The email has already been taken."},
     *                     "password": {"The password confirmation does not match."}
     *                 }
     *             )
     *         )
     *     )
     * )
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $dto = $this->userStoreMapper->fromRequestToDto($request);
        $user = $this->userStoreAction->handle($dto);
        $response = $this->userStoreMapper->fromModelToResource($user);
        return response()->json($response, Response::HTTP_CREATED);
    }
}
