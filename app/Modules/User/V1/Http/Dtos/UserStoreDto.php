<?php

namespace App\Modules\User\V1\Http\Dtos;

use Illuminate\Support\Facades\Hash;

class UserStoreDto
{
    private ?int $userType = null;

    public function __construct(
        private readonly string $name,
        private readonly string $document,
        private readonly string $email,
        private string $password,
    ) {
        $this->password = Hash::make($this->password);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDocument(): string
    {
        return $this->document;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUserType(): ?int
    {
        return $this->userType;
    }

    public function setUserType(?int $userType): void
    {
        $this->userType = $userType;
    }
}
