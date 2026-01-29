<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\AnalyzeCommentsSentimentMessage;
use App\Model\Sentiment\SentimentAnalyzerInterface;
use App\Repository\ProductRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class AnalyzeCommentsSentimentMessageHandler
{
    public function __construct(
        private ProductRepository $productRepository,
        private SentimentAnalyzerInterface $sentimentAnalyzer,
    ) {}

    public function __invoke(AnalyzeCommentsSentimentMessage $message): void
    {
        if (!$product = $this->productRepository->find($message->productId)) {
            return;
        }
        foreach ($product->getComments() as $comment) {
            if (!$comment->isAnalyzed()) {
                try {
                    $score = $this->sentimentAnalyzer->analyze($comment->getContent());

                    if ($score >= 0.66) {
                        $comment->markPositive();
                    } elseif ($score <= 0.33) {
                        $comment->markNegative();
                    } else {
                        $comment->markNeutral();
                    }

                    $comment->markStatusAsAnalyzed();
                } catch (\Throwable) {
                    $comment->markStatusAsFailed();
                }
            }
        }

        $this->productRepository->save($product);
    }
}
