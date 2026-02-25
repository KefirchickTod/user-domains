<?php

declare(strict_types=1);

namespace Src\Monitoring\Application\Responses;

use Src\Monitoring\Domain\CheckSettings;

final readonly class CheckSettingsResponse implements \JsonSerializable
{
    public function __construct(
        public int $interval_minutes,
        public int $timeout_seconds,
        public string $method
    ) {}

    public static function fromCheckSettings(CheckSettings $settings): self
    {
        return new self(
            interval_minutes: $settings->intervalMinutes(),
            timeout_seconds: $settings->timeoutSeconds(),
            method: $settings->method()->value
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'interval_minutes' => $this->interval_minutes,
            'timeout_seconds'  => $this->timeout_seconds,
            'method'           => $this->method,
        ];
    }
}
