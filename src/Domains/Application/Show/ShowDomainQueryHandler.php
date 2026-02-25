<?php

declare(strict_types=1);

namespace Src\Domains\Application\Show;

use Src\Domains\Application\Responses\DomainDetailResponse;
use Src\Domains\Domain\DomainId;
use Src\Domains\Domain\DomainRepositoryInterface;
use Src\Monitoring\Application\Responses\CheckLogResponse;
use Src\Monitoring\Application\Responses\CheckSettingsResponse;
use Src\Monitoring\Domain\CheckLog;
use Src\Monitoring\Domain\CheckLogRepositoryInterface;
use Src\Monitoring\Domain\CheckSettingsRepositoryInterface;

final readonly class ShowDomainQueryHandler
{
    public function __construct(
        private DomainRepositoryInterface        $domain_repository,
        private CheckSettingsRepositoryInterface $settings_repository,
        private CheckLogRepositoryInterface      $log_repository
    )
    {
    }

    public function handle(ShowDomainQuery $query): ?DomainDetailResponse
    {
        $domain = $this->domain_repository->findByIdForUser(
            new DomainId($query->domain_id),
            $query->user_id
        );

        if ($domain === null) {
            return null;
        }

        $settings = $this->settings_repository->findByDomainId($query->domain_id);
        $logs = $this->log_repository->findByDomainId($query->domain_id);

        return new DomainDetailResponse(
            id: $domain->id()->value(),
            name: $domain->name()->value(),
            url: $domain->url()->value(),
            is_active: $domain->isActive(),
            settings: $settings ? CheckSettingsResponse::fromCheckSettings($settings) : null,
            logs: array_map(
                static fn(CheckLog $log): CheckLogResponse => CheckLogResponse::fromCheckLog($log),
                $logs
            )
        );
    }
}
