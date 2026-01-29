<?php

declare(strict_types=1);

namespace App\Model\Parser;

use App\DTO\Product\ParsedProductData;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractProductParser implements ProductParserInterface
{
    abstract protected function extractImages(Crawler $crawler): array;
    abstract protected function extractComments(Crawler $crawler): array;

    public function __construct(
        private readonly HttpClientInterface $httpClient,
    ) {}

    public function parse(string $url): ParsedProductData
    {
        $html = $this->fetchHtml($url);
        $crawler = new Crawler($html);

        $title = $this->extractTitle($crawler);
        $images = array_values(array_filter(
            $this->extractImages($crawler),
            static fn (string $url): bool => filter_var($url, FILTER_VALIDATE_URL) && str_starts_with($url, 'http')
        ));
        $comments = array_values(array_filter(
            $this->extractComments($crawler),
            static fn (string $text): bool => trim($text) !== ''
        ));

        return new ParsedProductData(
            title: $title,
            images: $images,
            comments: $comments,
        );
    }

    protected function extractTitle(Crawler $crawler): string
    {
        return trim(
            $crawler->filter('h1')->first()->text('')
        );
    }

    private function fetchHtml(string $url): string
    {
        try {
            $response = $this->httpClient->request(Request::METHOD_GET, $url, [
                'timeout' => 10,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36',
                ],
            ]);
            $content = $response->getContent();
        } catch (\Throwable $throwable) {
            throw new \RuntimeException(
                sprintf(
                    'Failed to load page from "%s"',
                    $url,
                ),
                previous: $throwable
            );
        }

        return $content;
    }
}
