<?php

declare(strict_types=1);

namespace App\Model\Parser;

use App\DTO\Product\ParsedProductData;

readonly class ProductParseDataProvider
{
    public function __construct(
        private ProductParserRegistry $registry,
    ) {}

    public function provide(string $url): ParsedProductData
    {
        $host = parse_url($url, PHP_URL_HOST);
        $host = str_replace('www.', '', $host);

        return $this->registry->getParser($host)->parse($url);
    }
}
