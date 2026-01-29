<?php

declare(strict_types=1);

namespace App\DTO\Product;

readonly class ParsedProductData
{
    public function __construct(
        public string $title,
        /** @var string[] */
        public array $images,
        /** @var string[] */
        public array $comments,
    ) {}
}
