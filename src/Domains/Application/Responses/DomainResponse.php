<?php

declare(strict_types=1);

namespace Src\Domains\Application\Responses;

use Src\Domains\Domain\Domain;

final readonly class DomainResponse implements \JsonSerializable
{
    public function __construct(
        public int $id,
        public string $name,
        public string $url,
        public bool $is_active,
        public string $created_at,
        public ?bool $last_is_successful = null
    ) {}

    public static function fromDomain(Domain $domain, string $created_at = ''): self
    {
        return new self(
            id: $domain->id()->value(),
            name: $domain->name()->value(),
            url: $domain->url()->value(),
            is_active: $domain->isActive(),
            created_at: $created_at
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'url'                => $this->url,
            'is_active'          => $this->is_active,
            'created_at'         => $this->created_at,
            'last_is_successful' => $this->last_is_successful,
        ];
    }
}
