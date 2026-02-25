<?php

declare(strict_types=1);

namespace Src\Domains\Application\Create;

final readonly class CreateDomainCommand
{
    public function __construct(
        public int $user_id,
        public string $name,
        public string $url
    ) {}
}
