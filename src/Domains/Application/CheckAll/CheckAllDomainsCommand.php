<?php

declare(strict_types=1);

namespace Src\Domains\Application\CheckAll;

final readonly class CheckAllDomainsCommand
{
    public function __construct(
        public int $user_id
    ) {}
}
