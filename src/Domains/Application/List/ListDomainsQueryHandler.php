<?php

declare(strict_types=1);

namespace Src\Domains\Application\List;

use Src\Domains\Application\Responses\DomainResponse;
use Src\Domains\Domain\Domain;
use Src\Domains\Domain\DomainRepositoryInterface;
use Src\Monitoring\Domain\CheckLogRepositoryInterface;

final readonly class ListDomainsQueryHandler
{
    public function __construct(
        private DomainRepositoryInterface  $domain_repository,
        private CheckLogRepositoryInterface $log_repository
    ) {}

    public function handle(ListDomainsQuery $query): array
    {
        $domains = $this->domain_repository->findAllByUser($query->user_id);

        if (empty($domains)) {
            return [];
        }

        $domain_ids = array_map(static fn(Domain $d): int => $d->id()->value(), $domains);
        $last_status = $this->log_repository->findLastStatusByDomainIds($domain_ids);

        return array_map(
            static fn(Domain $domain) => new DomainResponse(
                id:                 $domain->id()->value(),
                name:               $domain->name()->value(),
                url:                $domain->url()->value(),
                is_active:          $domain->isActive(),
                created_at:         $domain->createdAt()->format('d.m.Y H:i'),
                last_is_successful: $last_status[$domain->id()->value()] ?? null
            ),
            $domains
        );
    }
}
