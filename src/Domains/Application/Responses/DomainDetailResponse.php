<?php

declare(strict_types=1);

namespace Src\Domains\Application\Responses;

use Src\Monitoring\Application\Responses\CheckLogResponse;
use Src\Monitoring\Application\Responses\CheckSettingsResponse;

final readonly class DomainDetailResponse
{
    /**
     * @param CheckLogResponse[] $logs
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $url,
        public bool $is_active,
        public ?CheckSettingsResponse $settings,
        public array $logs
    ) {}
}
