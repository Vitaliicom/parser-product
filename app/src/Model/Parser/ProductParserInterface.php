<?php

declare(strict_types=1);

namespace App\Model\Parser;

use App\DTO\Product\ParsedProductData;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.product_parser')]
interface ProductParserInterface
{
    public static function type(): string;
    public function parse(string $url): ParsedProductData;
}
