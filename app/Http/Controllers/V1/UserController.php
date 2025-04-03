<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\UserStoreRequest;
use App\Mappers\V1\UserMapper;
use App\Services\V1\UserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    public function __construct(
        private readonly UserService $userService,
    )
    {
    }

    public function index(): JsonResponse
    {
        $users = $this->userService->index();
        $statusCode = $users->isEmpty() ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        $response = UserMapper::toCollectionResource($users);
        return response()->json($response)->setStatusCode($statusCode);
    }


    public function store(UserStoreRequest $request): JsonResponse
    {
        $dto = UserMapper::toStoreDto($request);
        $user = $this->userService->store($dto);
        $response = UserMapper::toResource($user);
        return response()->json($response)->setStatusCode(Response::HTTP_CREATED);
    }


    public function show(string $uuid): JsonResponse
    {
        $user = $this->userService->show($uuid);
        $response = UserMapper::toDetailsResource($user);
        return response()->json($response)->setStatusCode(Response::HTTP_OK);
    }

}
