<?php

declare(strict_types=1);

namespace Src\Monitoring\Infrastructure\Persistence\Eloquent;

use App\Models\CheckSettingsModel;
use Psr\Log\LoggerInterface;
use Src\Monitoring\Domain\CheckSettings;
use Src\Monitoring\Domain\CheckSettingsId;
use Src\Monitoring\Domain\CheckSettingsRepositoryInterface;
use Src\Monitoring\Domain\Enums\CheckMethodEnum;

final readonly class EloquentCheckSettingsRepository implements CheckSettingsRepositoryInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function save(CheckSettings $settings): void
    {
        $this->logger->info('Saving check settings', ['domain_id' => $settings->domainId()]);

        CheckSettingsModel::updateOrCreate(
            ['domain_id' => $settings->domainId()],
            [
                'interval_minutes' => $settings->intervalMinutes(),
                'timeout_seconds'  => $settings->timeoutSeconds(),
                'method'           => $settings->method()->value,
            ]
        );

        $this->logger->info('Check settings saved', ['domain_id' => $settings->domainId()]);
    }

    public function findByDomainId(int $domain_id): ?CheckSettings
    {
        $model = CheckSettingsModel::where('domain_id', $domain_id)->first();

        return $model ? $this->toDomain($model) : null;
    }

    private function toDomain(CheckSettingsModel $model): CheckSettings
    {
        return new CheckSettings(
            id: new CheckSettingsId($model->id),
            domain_id: $model->domain_id,
            interval_minutes: $model->interval_minutes,
            timeout_seconds: $model->timeout_seconds,
            method: CheckMethodEnum::from($model->method)
        );
    }
}
