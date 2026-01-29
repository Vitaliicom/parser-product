<?php

declare(strict_types=1);

namespace App\Model\Parser;

use Symfony\Component\DomCrawler\Crawler;

class RozetkaProductParser extends AbstractProductParser
{
    public static function type(): string
    {
        return 'rozetka.com.ua';
    }

    protected function extractImages(Crawler $crawler): array
    {
        return $crawler->filter('.main-slider__item img')->each(
            static fn (Crawler $node) => $node->attr('src')
        );
    }

    protected function extractComments(Crawler $crawler): array
    {
        return $crawler->filter('.comment__body-wrapper')->each(
            static fn (Crawler $node) => $node->text()
        );
    }
}
