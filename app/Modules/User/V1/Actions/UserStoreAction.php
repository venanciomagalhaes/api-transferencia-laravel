<?php

namespace App\Modules\User\V1\Actions;

use App\Modules\Common\V1\Services\Logger\LoggerServiceInterface;
use App\Modules\Common\V1\Services\Transaction\TransactionServiceInterface;
use App\Modules\User\V1\Enums\UserTypeNameEnum;
use App\Modules\User\V1\Events\UserCreated;
use App\Modules\User\V1\Exceptions\InvalidDocumentException;
use App\Modules\User\V1\Exceptions\UserAlreadyExistsException;
use App\Modules\User\V1\Http\Dtos\UserStoreDto;
use App\Modules\User\V1\Http\Mappers\UserStoreMapper;
use App\Modules\User\V1\Models\User;
use App\Modules\User\V1\Repositories\UserRepositoryInterface;
use App\Modules\User\V1\Repositories\UserTypeRepositoryInterface;
use App\Modules\User\V1\Services\CpfCnpjValidationService;
use Exception;

/**
 * Class UserStoreAction
 *
 * Responsável por orquestrar o processo de criação de um novo usuário,
 * realizando todas as validações necessárias, definição de tipo de usuário,
 * persistência dos dados e notificação por evento.
 *
 * Esta ação centraliza o processo de criação e utiliza um serviço de logger
 * desacoplado para garantir rastreabilidade e manutenibilidade dos logs.
 *
 * Fluxo principal:
 *  - Verifica se o usuário já existe por e-mail ou documento;
 *  - Valida o CPF ou CNPJ;
 *  - Define o tipo de usuário (comum ou lojista);
 *  - Persiste os dados no banco de forma transacional;
 *  - Dispara evento de criação para integração com outros módulos (ex: carteira).
 *
 * @package App\Modules\User\V1\Actions
 */
readonly class UserStoreAction
{
    /**
     * Construtor da classe.
     *
     * @param UserRepositoryInterface $userRepository Repositório para acesso e persistência de usuários.
     * @param UserTypeRepositoryInterface $userTypeRepository Repositório para tipos de usuário.
     * @param UserStoreMapper $userStoreMapper Mapper para converter DTO em estrutura de persistência.
     * @param CpfCnpjValidationService $cpfCnpjValidationService Serviço de validação de CPF/CNPJ.
     * @param LoggerServiceInterface $loggerService Serviço centralizado para logging da aplicação.
     * @param TransactionServiceInterface $transactionService Serviço para gerenciamento de transações.
     */
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserTypeRepositoryInterface $userTypeRepository,
        private UserStoreMapper $userStoreMapper,
        private CpfCnpjValidationService $cpfCnpjValidationService,
        private LoggerServiceInterface $loggerService,
        private TransactionServiceInterface $transactionService,
    ) {
    }

    /**
     * Executa o processo de criação de um novo usuário.
     *
     * A operação é executada dentro de uma transação de banco de dados para garantir consistência.
     * Dispara o evento `UserCreated` ao final, permitindo que módulos externos (como carteira)
     * executem ações reativas à criação do usuário.
     *
     * Passos do fluxo:
     * 1. Verifica se já existe um usuário com o mesmo documento ou e-mail.
     * 2. Define o tipo do usuário (comum ou lojista) com base no documento informado.
     * 3. Mapeia o DTO para estrutura de persistência.
     * 4. Persiste o usuário no banco de dados.
     * 5. Dispara evento `UserCreated`.
     *
     * @param UserStoreDto $dto Objeto de transferência com os dados do usuário.
     * @return User O usuário recém-criado.
     *
     * @throws UserAlreadyExistsException Se já houver um usuário com o mesmo e-mail ou documento.
     * @throws InvalidDocumentException Se o documento informado for inválido.
     * @throws Exception Para quaisquer erros de execução.
     */
    public function handle(UserStoreDto $dto): User
    {
        $this->loggerService->info('Starting user creation process.');

        try {
            return $this->transactionService->run(function () use ($dto) {

                $this->loggerService->info('Checking if user already exists.');

                $user = $this->userRepository->getUserByDocumentOrEmail(
                    document: $dto->getDocument(),
                    email: $dto->getEmail()
                );

                $this->throwExceptionIfUserAlreadyExists($user);

                $this->loggerService->info('Setting user type based on document.');

                $this->setUserType($dto);

                $data = $this->userStoreMapper->fromDtoToPersistency($dto);

                $this->loggerService->info('Creating user record in database.');

                $user = $this->userRepository->create($data);

                $this->loggerService->info('User created successfully. Dispatching UserCreated event.');

                event(new UserCreated(user: $user, amount: 1000.00));

                return $user->load('wallet');
            });
        } catch (Exception $e) {
            $this->loggerService->error('An error occurred while creating the user: ' . $e->getMessage());
            $this->loggerService->info('Database transaction rolled back.');

            throw $e;
        }
    }

    /**
     * Verifica se o usuário já existe e, em caso positivo, lança exceção.
     *
     * @param User|null $user Instância existente ou null.
     * @throws UserAlreadyExistsException Se o usuário já estiver cadastrado.
     */
    public function throwExceptionIfUserAlreadyExists(?User $user): void
    {
        if ($user instanceof User) {
            $this->loggerService->warning('Attempt to create user that already exists.');
            throw new UserAlreadyExistsException();
        }
    }

    /**
     * Define o tipo do usuário com base no documento (CPF ou CNPJ).
     *
     * Caso o documento seja CPF, o tipo será COMMON.
     * Caso seja CNPJ, o tipo será MERCHANT.
     *
     * @param UserStoreDto $dto DTO do usuário com o documento informado.
     * @throws InvalidDocumentException Se o documento não for válido.
     */
    public function setUserType(UserStoreDto $dto): void
    {
        $document = preg_replace('/\D/', '', $dto->getDocument());

        if(strlen($document) == 11 && $this->cpfCnpjValidationService->isCpf($document)) {
                $userType = $this->userTypeRepository->getUserTypeByName(UserTypeNameEnum::COMMON);
                $this->loggerService->info('User type set to COMMON based on CPF.');
                $dto->setUserType($userType->id);
                return;
        }

        if ($this->cpfCnpjValidationService->isCnpj($document)) {
            $userType = $this->userTypeRepository->getUserTypeByName(UserTypeNameEnum::MERCHANT);
            $this->loggerService->info('User type set to MERCHANT based on CNPJ.');
            $dto->setUserType($userType->id);
            return;
        }

        $this->loggerService->error('Invalid document provided for user type determination.');
        throw new InvalidDocumentException();
    }
}
