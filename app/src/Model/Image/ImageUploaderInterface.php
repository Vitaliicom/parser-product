<?php

declare(strict_types=1);

namespace App\Model\Image;

interface ImageUploaderInterface
{
    public function upload(string $url): string;
}
