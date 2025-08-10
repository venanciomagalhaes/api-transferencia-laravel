<?php

namespace App\Modules\Transaction\V1\Http\Controllers;

use App\Modules\Common\V1\Http\Controllers\Controller;
use App\Modules\Transaction\V1\Action\TransferAction;
use App\Modules\Transaction\V1\Http\Requests\TransferRequest;
use App\Modules\Transaction\V1\Mappers\TransferMapper;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransferMapper $transferMapper,
        private readonly TransferAction $transferAction
    ) {}

    /**
     * @OA\Post(
     *     path="/api/v1/transfer",
     *     summary="Realiza uma transação entre clientes",
     *     tags={"Transaction"},
     *     operationId="makeTransfer",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"payer", "payee", "value"},
     *
     *             @OA\Property(property="payer", type="string", format="uuid", example="9f87526a-f5fb-45ec-b022-c89203c6e113"),
     *             @OA\Property(property="payee", type="string", format="uuid", example="efb9f3a3-b8d1-4389-9bd6-6cef92edfc6b"),
     *             @OA\Property(property="value", type="number", format="float", example=10.00)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Transfer completed successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Transfer send successfully"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized transfer attempt",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="This payer is not authorized to perform this transfer.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function transfer(TransferRequest $request): JsonResponse
    {
        $dto = $this->transferMapper->fromRequestToDto($request);
        $this->transferAction->handle($dto);

        return response()->json($this->transferMapper->getResourceResponse(), Response::HTTP_OK);
    }
}
