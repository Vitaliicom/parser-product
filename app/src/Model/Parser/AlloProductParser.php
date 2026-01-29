<?php

declare(strict_types=1);

namespace App\Model\Parser;

use Symfony\Component\DomCrawler\Crawler;

class AlloProductParser extends AbstractProductParser
{
    public static function type(): string
    {
        return 'allo.ua';
    }

    protected function extractImages(Crawler $crawler): array
    {
        return $crawler->filter('.main-gallery__link img')->each(
            static fn (Crawler $node) => $node->attr('src')
        );
    }

    protected function extractComments(Crawler $crawler): array
    {
        return $crawler->filter('.product-comment__main .product-comment__text')->each(
            static fn (Crawler $node) => $node->text()
        );
    }
}
