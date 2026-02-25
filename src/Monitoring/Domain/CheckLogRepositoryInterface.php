<?php

declare(strict_types=1);

namespace Src\Monitoring\Domain;

interface CheckLogRepositoryInterface
{
    public function save(CheckLog $log): void;

    public function findLastByDomainId(int $domain_id): ?CheckLog;

    public function findByDomainId(int $domain_id, int $limit = 50): array;

    /**
     * Returns last is_successful status per domain in a single query.
     *
     * @param  int[]                $domain_ids
     * @return array<int, bool|null> domain_id → last is_successful (null if never checked)
     */
    public function findLastStatusByDomainIds(array $domain_ids): array;

    /**
     * Returns all domains that are due for a check.
     * A domain is due when: last checked_at + interval_minutes <= now (or never checked).
     *
     * @return array<int, array{domain_id: int, url: string, settings: CheckSettings}>
     */
    public function findDomainsDueForCheck(): array;
}
