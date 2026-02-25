<?php

declare(strict_types=1);

namespace Src\Domains\Application\CheckNow;

use App\Jobs\CheckDomainJob;
use Src\Domains\Domain\DomainId;
use Src\Domains\Domain\DomainRepositoryInterface;
use Src\Monitoring\Domain\CheckSettingsRepositoryInterface;

final readonly class CheckDomainNowCommandHandler
{
    public function __construct(
        private DomainRepositoryInterface $domain_repository,
        private CheckSettingsRepositoryInterface $settings_repository
    ) {}

    public function handle(CheckDomainNowCommand $command): void
    {
        $domain = $this->domain_repository->findByIdForUser(
            new DomainId($command->domain_id),
            $command->user_id
        );

        if ($domain === null) {
            return;
        }

        $settings = $this->settings_repository->findByDomainId($command->domain_id);

        CheckDomainJob::dispatch(
            domain_id:       $domain->id()->value(),
            url:             $domain->url()->value(),
            method:          $settings?->method()->value ?? 'HEAD',
            timeout_seconds: $settings?->timeoutSeconds() ?? 10
        );
    }
}
