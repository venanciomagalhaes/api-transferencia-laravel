<?php

namespace App\Services\V1;

use App\Dtos\V1\TransferDto;
use App\Enums\PermissionsEnum;
use App\Mappers\V1\TransferMapper;
use App\Models\Transfer;
use App\Models\User;
use App\Repositories\V1\TransferRepository;
use App\Repositories\V1\UserRepository;
use App\Repositories\V1\WalletRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

readonly class TransferService
{
    public function __construct(
        private UserRepository $userRepository,
        private AuthorizationService $authorizationService,
        private NotificationService $notificationService,
        private WalletRepository $walletRepository,
        private TransferRepository $transferRepository,
    ) {}

    public function makeTransfer(TransferDto $dto): Transfer
    {
        return DB::transaction(function () use ($dto) {
            $payer = $this->userRepository->findByUuid($dto->getPayerUuid());
            $payee = $this->userRepository->findByUuid($dto->getPayeeUuid());

            $this->ensureDifferentUsers($payer, $payee);

            $this->ensurePermission(
                user: $payer,
                permission: PermissionsEnum::MAKE_A_TRANSFER,
                message: 'The payer is not authorized to make transfers.'
            );

            $this->ensurePermission(
                user: $payee,
                permission: PermissionsEnum::RECEIVE_A_TRANSFER,
                message: 'The payee is not authorized to receive transfers.'
            );

            $transferValue = $dto->getValue();

            if ($payer->wallet->amount < $transferValue) {
                abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'The payer does not have sufficient balance.');
            }

            $this->authorizationService->verify();

            $this->walletRepository->decrementAmount($payer, $transferValue);
            $this->walletRepository->incrementAmount($payee, $transferValue);

            $dto->setPayerId($payer->id);
            $dto->setPayeeId($payee->id);

            $transfer = $this->transferRepository->create(TransferMapper::toArrayFromDto($dto));

            $this->notificationService->notify();

            return $transfer;
        });
    }

    private function ensurePermission(User $user, PermissionsEnum $permission, string $message): void
    {
        if (!Gate::allows('hasPermission', [$user, $permission])) {
            abort(Response::HTTP_FORBIDDEN, $message);
        }
    }

    private function ensureDifferentUsers(User $payer, User $payee): void
    {
        if ($payer->id === $payee->id) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'The payer and payee must be different users.');
        }
    }
}
