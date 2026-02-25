<?php

declare(strict_types=1);

namespace Src\Monitoring\Domain;

interface CheckSettingsRepositoryInterface
{
    public function save(CheckSettings $settings): void;

    public function findByDomainId(int $domain_id): ?CheckSettings;
}
