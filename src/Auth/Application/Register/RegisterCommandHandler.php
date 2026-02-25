<?php

declare(strict_types=1);

namespace Src\Auth\Application\Register;

use App\Models\User;
use Src\Auth\Domain\UserRepositoryInterface;

final readonly class RegisterCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    public function handle(RegisterCommand $command): User
    {
        return $this->repository->create(
            name: $command->name,
            email: $command->email,
            password: $command->password
        );
    }
}
