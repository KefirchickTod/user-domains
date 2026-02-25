<?php

declare(strict_types=1);

namespace Src\Domains\Domain;

interface DomainRepositoryInterface
{
    public function save(Domain $domain): Domain;

    public function findById(DomainId $id): ?Domain;

    public function findByIdForUser(DomainId $id, int $user_id): ?Domain;

    /** @return Domain[] */
    public function findAllByUser(int $user_id): array;

    public function delete(DomainId $id): void;
}
