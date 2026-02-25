<?php

declare(strict_types=1);

namespace Src\Domains\Application\List;

final readonly class ListDomainsQuery
{
    public function __construct(
        public int $user_id
    ) {}
}
