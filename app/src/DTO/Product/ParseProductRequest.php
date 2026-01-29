<?php

declare(strict_types=1);

namespace App\DTO\Product;

use Symfony\Component\Validator\Constraints;

readonly class ParseProductRequest
{
    public function __construct(
        #[Constraints\NotBlank]
        #[Constraints\Length(max: 255)]
        #[Constraints\Url]
        public string $url,
    ) {}
}
