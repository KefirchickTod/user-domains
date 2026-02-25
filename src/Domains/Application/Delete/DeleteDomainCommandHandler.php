<?php

declare(strict_types=1);

namespace Src\Domains\Application\Delete;

use Src\Domains\Domain\DomainId;
use Src\Domains\Domain\DomainRepositoryInterface;

final readonly class DeleteDomainCommandHandler
{
    public function __construct(
        private DomainRepositoryInterface $repository,
    ) {}

    public function handle(DeleteDomainCommand $command): void
    {
        $this->repository->delete(new DomainId($command->domain_id));
    }
}
