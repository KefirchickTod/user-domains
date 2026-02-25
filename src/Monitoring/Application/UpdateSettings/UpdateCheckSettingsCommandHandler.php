<?php

declare(strict_types=1);

namespace Src\Monitoring\Application\UpdateSettings;

use Psr\Log\LoggerInterface;
use Src\Monitoring\Domain\CheckSettingsRepositoryInterface;
use Src\Monitoring\Domain\Enums\CheckMethodEnum;

final readonly class UpdateCheckSettingsCommandHandler
{
    public function __construct(
        private CheckSettingsRepositoryInterface $settings_repository,
        private LoggerInterface                  $logger
    ) {}

    public function handle(UpdateCheckSettingsCommand $command): void
    {
        $settings = $this->settings_repository->findByDomainId($command->domain_id);

        if ($settings === null) {
            return;
        }

        $settings->update(
            interval_minutes: $command->interval_minutes,
            timeout_seconds:  $command->timeout_seconds,
            method:           CheckMethodEnum::from($command->method)
        );

        $this->settings_repository->save($settings);

        $this->logger->info('Check settings updated', ['domain_id' => $command->domain_id]);
    }
}
