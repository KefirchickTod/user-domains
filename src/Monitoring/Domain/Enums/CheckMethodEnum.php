<?php

declare(strict_types=1);

namespace Src\Monitoring\Domain\Enums;

enum CheckMethodEnum: string
{
    case GET  = 'GET';
    case HEAD = 'HEAD';
}
