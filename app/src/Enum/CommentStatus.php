<?php

declare(strict_types=1);

namespace App\Enum;

enum CommentStatus: string
{
    case NEW = 'new';
    case ANALYZED = 'analyzed';
    case FAILED = 'failed';
}
