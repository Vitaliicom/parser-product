<?php

declare(strict_types=1);

namespace App\Model\Sentiment;

interface SentimentAnalyzerInterface
{
    public function analyze(string $text): float;
}
