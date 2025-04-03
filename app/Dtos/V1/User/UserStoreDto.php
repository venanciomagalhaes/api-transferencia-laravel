<?php

namespace App\Dtos\V1\User;

class UserStoreDto
{

    private int $roleId;


    public function __construct(
        private readonly string $name,
    private readonly string     $email,
    private readonly string     $cpf_cnpj,
    private readonly string     $password,
    private readonly string     $roleName,
    private readonly string     $password_confirmation,
    ) {

    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCpfCnpj(): string
    {
        return preg_replace('/\D/', '', $this->cpf_cnpj);;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoleName(): string
    {
        return $this->roleName;
    }

    public function getPasswordConfirmation(): string
    {
        return $this->password_confirmation;
    }

    public function setRoleId(int $id): void
    {
        $this->roleId = $id;
    }


    public function getRoleId(): int
    {
        return $this->roleId;
    }
}
