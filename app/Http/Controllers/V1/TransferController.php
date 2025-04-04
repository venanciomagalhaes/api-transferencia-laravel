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
    )
    {
    }

    public function transfer(TransferRequest $request): \Illuminate\Http\JsonResponse
    {
        $dto = TransferMapper::toTransferDto($request);
        $transfer = $this->transferService->makeTransfer($dto);
        $response = TransferMapper::toResource($transfer);
        return response()->json($response)->setStatusCode(Response::HTTP_CREATED);
    }
}
