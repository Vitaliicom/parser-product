<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Comment;

final readonly class CommentFactory
{
    public function create(string $content): Comment
    {
        return new Comment($content);
    }
}
