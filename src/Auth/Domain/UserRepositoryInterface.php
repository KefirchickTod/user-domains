<?php

declare(strict_types=1);

namespace Src\Auth\Domain;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(string $name, string $email, string $password): User;
}
