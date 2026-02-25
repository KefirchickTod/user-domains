<?php

declare(strict_types=1);

namespace Src\Monitoring\Application\UpdateSettings;

final readonly class UpdateCheckSettingsCommand
{
    public function __construct(
        public int $domain_id,
        public int $user_id,
        public int $interval_minutes,
        public int $timeout_seconds,
        public string $method
    ) {}
}
