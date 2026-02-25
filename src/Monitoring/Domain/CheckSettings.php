<?php

declare(strict_types=1);

namespace Src\Monitoring\Domain;

use Src\Monitoring\Domain\Enums\CheckMethodEnum;

final class CheckSettings
{
    private const DEFAULT_INTERVAL_MIN = 5;
    private const DEFAULT_TIMEOUT_SEC = 10;

    public function __construct(
        private readonly CheckSettingsId $id,
        private readonly int             $domain_id,
        private int                      $interval_minutes,
        private int                      $timeout_seconds,
        private CheckMethodEnum          $method
    ) {}

    public static function createDefault(int $domain_id): self
    {
        return new self(
            id: new CheckSettingsId(0),
            domain_id: $domain_id,
            interval_minutes: self::DEFAULT_INTERVAL_MIN,
            timeout_seconds: self::DEFAULT_TIMEOUT_SEC,
            method: CheckMethodEnum::HEAD
        );
    }

    public function update(int $interval_minutes, int $timeout_seconds, CheckMethodEnum $method): void
    {
        $this->interval_minutes = $interval_minutes;
        $this->timeout_seconds = $timeout_seconds;
        $this->method = $method;
    }

    public function id(): CheckSettingsId
    {
        return $this->id;
    }

    public function domainId(): int
    {
        return $this->domain_id;
    }

    public function intervalMinutes(): int
    {
        return $this->interval_minutes;
    }

    public function timeoutSeconds(): int
    {
        return $this->timeout_seconds;
    }

    public function method(): CheckMethodEnum
    {
        return $this->method;
    }
}
