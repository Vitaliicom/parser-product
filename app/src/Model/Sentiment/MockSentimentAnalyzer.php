<?php

declare(strict_types=1);

namespace App\Model\Sentiment;

use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(id: SentimentAnalyzerInterface::class)]
class MockSentimentAnalyzer implements SentimentAnalyzerInterface
{
    public function analyze(string $text): float
    {
        $hash = crc32($text);
        return ($hash % 1000) / 1000;
    }
}
