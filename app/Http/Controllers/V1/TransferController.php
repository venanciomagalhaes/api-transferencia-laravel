<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\TransferRequest;
use App\Mappers\V1\TransferMapper;
use App\Services\V1\TransferService;
use Symfony\Component\HttpFoundation\Response;

class TransferController extends Controller
{
    public function __construct(
        private TransferService $transferService
    ) {}

    /**
     * @OA\Post(
     *     path="/api/v1/transfer",
     *     summary="Realizar transferência",
     *     description="Realiza uma transferência de saldo entre usuários, pelo UUID. O pagador (payer) deve ter a role 'customer'. O recebedor (payee) pode ter a role 'customer' ou 'merchant'.",
     *     operationId="makeTransfer",
     *     tags={"Transfers"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"payee", "payer", "value"},
     *
     *             @OA\Property(property="payee", type="string", format="uuid", example="17878d9a-c6ee-400f-8ee4-54d068e6eccf", description="UUID do recebedor da transferência (merchant ou customer)"),
     *             @OA\Property(property="payer", type="string", format="uuid", example="7487ca27-4302-4871-8ad6-52b185c8ea33", description="UUID do pagador (deve ser um usuário com role 'customer')"),
     *             @OA\Property(property="value", type="number", format="float", example=10.80, description="Valor da transferência")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Transferência realizada com sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Transfer make successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="payer", type="string", example="Pessoa Física"),
     *                 @OA\Property(property="payee", type="string", example="Pessoa Física"),
     *                 @OA\Property(property="amount", type="number", format="float", example=10.8)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *        description="Erro de validação (campos inválidos ou ausentes)"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *     )
     * )
     */
    public function transfer(TransferRequest $request): \Illuminate\Http\JsonResponse
    {
        $dto = TransferMapper::toTransferDto($request);
        $transfer = $this->transferService->makeTransfer($dto);
        $response = TransferMapper::toResource($transfer);

        return response()->json($response)->setStatusCode(Response::HTTP_CREATED);
    }
}
