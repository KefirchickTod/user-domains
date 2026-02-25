<?php

declare(strict_types=1);

namespace Src\Domains\Application\CheckAll;

use App\Jobs\CheckDomainJob;
use Src\Domains\Domain\Domain;
use Src\Domains\Domain\DomainRepositoryInterface;
use Src\Monitoring\Domain\CheckSettingsRepositoryInterface;

final readonly class CheckAllDomainsCommandHandler
{
    private const DEFAULT_METHOD = 'HEAD';

    public function __construct(
        private DomainRepositoryInterface $domain_repository,
        private CheckSettingsRepositoryInterface $settings_repository
    ) {}

    public function handle(CheckAllDomainsCommand $command): void
    {
        $domains = $this->domain_repository->findAllByUser($command->user_id);

        foreach ($domains as $domain) {
            /** @var Domain $domain */
            if (!$domain->isActive()) {
                continue;
            }

            $settings = $this->settings_repository->findByDomainId($domain->id()->value());

            CheckDomainJob::dispatch(
                domain_id:       $domain->id()->value(),
                url:             $domain->url()->value(),
                method:          $settings?->method()->value ?? 'HEAD',
                timeout_seconds: $settings?->timeoutSeconds() ?? 10
            );
        }
    }
}
