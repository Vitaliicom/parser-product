<?php

declare(strict_types=1);

namespace App\Enum;

enum ProductStatus: string
{
    case NEW = 'new';
    case PARSING = 'parsing';
    case DONE = 'done';
    case FAILED = 'failed';
}
