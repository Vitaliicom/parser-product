<?php

declare(strict_types=1);

namespace App\Enum;

enum ImageStatus: string
{
    case NEW = 'new';
    case STORED = 'stored';
    case FAILED = 'failed';
}
