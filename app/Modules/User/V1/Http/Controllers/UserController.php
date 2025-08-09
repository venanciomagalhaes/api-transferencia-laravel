<?php

namespace App\Modules\User\V1\Http\Controllers;

use App\Modules\Common\V1\Http\Controllers\Controller;
use App\Modules\User\V1\Actions\UserStoreAction;
use App\Modules\User\V1\Http\Mappers\UserStoreMapper;
use App\Modules\User\V1\Http\Requests\UserStoreRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly UserStoreMapper $userStoreMapper,
        private readonly UserStoreAction $userStoreAction
    )
    {
    }


    public function store(UserStoreRequest $request): JsonResponse
    {
        $dto = $this->userStoreMapper->fromRequestToDto($request);
        $user = $this->userStoreAction->handle($dto);
        $response = $this->userStoreMapper->fromModelToResource($user);
        return response()->json($response, Response::HTTP_CREATED);
    }
}
