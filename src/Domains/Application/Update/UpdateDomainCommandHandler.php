<?php

declare(strict_types=1);

namespace Src\Domains\Application\Update;

use Src\Domains\Domain\DomainId;
use Src\Domains\Domain\DomainName;
use Src\Domains\Domain\DomainRepositoryInterface;
use Src\Domains\Domain\DomainUrl;

final readonly class UpdateDomainCommandHandler
{
    public function __construct(
        private DomainRepositoryInterface $repository,
    ) {}

    public function handle(UpdateDomainCommand $command): void
    {
        $domain = $this->repository->findById(new DomainId($command->domain_id));

        if ($domain === null) {
            return;
        }

        $domain->update(
            name:      new DomainName($command->name),
            url:       new DomainUrl($command->url),
            is_active: $command->is_active
        );

        $this->repository->save($domain);
    }
}
