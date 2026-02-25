<?php

declare(strict_types=1);

namespace Src\Monitoring\Domain;

final class CheckLog
{
    public function __construct(
        private readonly CheckLogId $id,
        private readonly int $domain_id,
        private readonly ?int $status_code,
        private readonly ?int $response_time_ms,
        private readonly bool $is_successful,
        private readonly ?string $error_message,
        private readonly \DateTimeImmutable $checked_at
    ) {}

    public static function record(
        int $domain_id,
        ?int $status_code,
        ?int $response_time_ms,
        bool $is_successful,
        ?string $error_message
    ): self {
        return new self(
            id: new CheckLogId(0),
            domain_id: $domain_id,
            status_code: $status_code,
            response_time_ms: $response_time_ms,
            is_successful: $is_successful,
            error_message: $error_message,
            checked_at: new \DateTimeImmutable()
        );
    }

    public function id(): CheckLogId
    {
        return $this->id;
    }

    public function domainId(): int
    {
        return $this->domain_id;
    }

    public function statusCode(): ?int
    {
        return $this->status_code;
    }

    public function responseTimeMs(): ?int
    {
        return $this->response_time_ms;
    }

    public function isSuccessful(): bool
    {
        return $this->is_successful;
    }

    public function errorMessage(): ?string
    {
        return $this->error_message;
    }

    public function checkedAt(): \DateTimeImmutable
    {
        return $this->checked_at;
    }
}
