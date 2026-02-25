<?php

declare(strict_types=1);

namespace Src\Auth\Infrastructure\Persistence\Eloquent;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Src\Auth\Domain\UserRepositoryInterface;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function create(string $name, string $email, string $password): User
    {
        return User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
        ]);
    }
}
