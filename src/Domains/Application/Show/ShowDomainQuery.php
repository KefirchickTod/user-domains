<?php

declare(strict_types=1);

namespace Src\Domains\Application\Show;

final readonly class ShowDomainQuery
{
    public function __construct(
        public int $domain_id,
        public int $user_id
    ) {}
}
