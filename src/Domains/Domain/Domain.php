<?php

declare(strict_types=1);

namespace Src\Domains\Domain;

use DateTimeImmutable;
use Src\Shared\Domain\Aggregate\AggregateRoot;

final class Domain extends AggregateRoot
{
    public function __construct(
        private DomainId $id,
        private readonly int $user_id,
        private DomainName $name,
        private DomainUrl $url,
        private bool $is_active,
        private readonly DateTimeImmutable $created_at = new DateTimeImmutable()
    ) {}

    public static function create(
        int $user_id,
        DomainName $name,
        DomainUrl $url
    ): self {
        return new self(
            id: new DomainId(0),
            user_id: $user_id,
            name: $name,
            url: $url,
            is_active: true
        );
    }

    public function update(DomainName $name, DomainUrl $url, bool $is_active): void
    {
        $this->name      = $name;
        $this->url       = $url;
        $this->is_active = $is_active;
    }

    public function id(): DomainId
    {
        return $this->id;
    }

    public function userId(): int
    {
        return $this->user_id;
    }

    public function name(): DomainName
    {
        return $this->name;
    }

    public function url(): DomainUrl
    {
        return $this->url;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->created_at;
    }
}
