<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Image;

final readonly class ImageFactory
{
    public function create(string $url): Image
    {
        return new Image($url);
    }
}
