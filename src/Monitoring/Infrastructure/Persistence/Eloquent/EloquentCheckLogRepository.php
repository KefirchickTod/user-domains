<?php

declare(strict_types=1);

namespace Src\Monitoring\Infrastructure\Persistence\Eloquent;

use App\Models\CheckLogModel;
use App\Models\CheckSettingsModel;
use App\Models\DomainModel;
use Psr\Log\LoggerInterface;
use Src\Monitoring\Domain\CheckLog;
use Src\Monitoring\Domain\CheckLogId;
use Src\Monitoring\Domain\CheckLogRepositoryInterface;
use Src\Monitoring\Domain\CheckSettings;
use Src\Monitoring\Domain\CheckSettingsId;
use Src\Monitoring\Domain\Enums\CheckMethodEnum;

final readonly class EloquentCheckLogRepository implements CheckLogRepositoryInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function save(CheckLog $log): void
    {
        $this->logger->info('Saving check log', ['domain_id' => $log->domainId()]);

        CheckLogModel::create([
            'domain_id'        => $log->domainId(),
            'status_code'      => $log->statusCode(),
            'response_time_ms' => $log->responseTimeMs(),
            'is_successful'    => $log->isSuccessful(),
            'error_message'    => $log->errorMessage(),
            'checked_at'       => $log->checkedAt()->format('Y-m-d H:i:s'),
        ]);

        $this->logger->info('Check log saved', ['domain_id' => $log->domainId()]);
    }

    public function findLastByDomainId(int $domain_id): ?CheckLog
    {
        $model = CheckLogModel::where('domain_id', $domain_id)
            ->orderByDesc('checked_at')
            ->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findLastStatusByDomainIds(array $domain_ids): array
    {
        if (empty($domain_ids)) {
            return [];
        }

        $rows = CheckLogModel::query()
            ->selectRaw('domain_id, is_successful')
            ->whereIn('domain_id', $domain_ids)
            ->whereIn('id', function ($sub) use ($domain_ids) {
                $sub->selectRaw('MAX(id)')
                    ->from('check_logs')
                    ->whereIn('domain_id', $domain_ids)
                    ->groupBy('domain_id');
            })
            ->get();

        $map = array_fill_keys($domain_ids, null);

        foreach ($rows as $row) {
            $map[$row->domain_id] = (bool) $row->is_successful;
        }

        return $map;
    }

    public function findByDomainId(int $domain_id, int $limit = 50): array
    {
        return CheckLogModel::where('domain_id', $domain_id)
            ->orderByDesc('checked_at')
            ->limit($limit)
            ->get()
            ->map(fn (CheckLogModel $model) => $this->toDomain($model))
            ->all();
    }

    public function findDomainsDueForCheck(): array
    {
        return DomainModel::query()
            ->where('is_active', true)
            ->with('checkSettings')
            ->get()
            ->filter(fn (DomainModel $domain) => $this->isDomainDue($domain))
            ->map(fn (DomainModel $domain) => [
                'domain_id' => $domain->id,
                'url'       => $domain->url,
                'settings'  => $this->settingsToDomain($domain->checkSettings),
            ])
            ->values()
            ->all();
    }

    private function isDomainDue(DomainModel $domain): bool
    {
        if ($domain->checkSettings === null) {
            return false;
        }

        $last_log = CheckLogModel::where('domain_id', $domain->id)
            ->orderByDesc('checked_at')
            ->first();

        if ($last_log === null) {
            return true;
        }

        $interval_minutes = $domain->checkSettings->interval_minutes;
        $next_check_at    = $last_log->checked_at->addMinutes($interval_minutes);

        return now()->greaterThanOrEqualTo($next_check_at);
    }

    private function settingsToDomain(CheckSettingsModel $model): CheckSettings
    {
        return new CheckSettings(
            id: new CheckSettingsId($model->id),
            domain_id: $model->domain_id,
            interval_minutes: $model->interval_minutes,
            timeout_seconds: $model->timeout_seconds,
            method: CheckMethodEnum::from($model->method)
        );
    }

    private function toDomain(CheckLogModel $model): CheckLog
    {
        return new CheckLog(
            id: new CheckLogId($model->id),
            domain_id: $model->domain_id,
            status_code: $model->status_code,
            response_time_ms: $model->response_time_ms,
            is_successful: $model->is_successful,
            error_message: $model->error_message,
            checked_at: new \DateTimeImmutable($model->checked_at->toDateTimeString())
        );
    }
}
