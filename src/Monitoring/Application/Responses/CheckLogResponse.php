<?php

declare(strict_types=1);

namespace Src\Monitoring\Application\Responses;

use Src\Monitoring\Domain\CheckLog;

final readonly class CheckLogResponse implements \JsonSerializable
{
    public function __construct(
        public int $id,
        public ?int $status_code,
        public ?int $response_time_ms,
        public bool $is_successful,
        public ?string $error_message,
        public string $checked_at
    ) {}

    public static function fromCheckLog(CheckLog $log): self
    {
        return new self(
            id: $log->id()->value(),
            status_code: $log->statusCode(),
            response_time_ms: $log->responseTimeMs(),
            is_successful: $log->isSuccessful(),
            error_message: $log->errorMessage(),
            checked_at: $log->checkedAt()->format('Y-m-d H:i:s')
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id'               => $this->id,
            'status_code'      => $this->status_code,
            'response_time_ms' => $this->response_time_ms,
            'is_successful'    => $this->is_successful,
            'error_message'    => $this->error_message,
            'checked_at'       => $this->checked_at,
        ];
    }
}
