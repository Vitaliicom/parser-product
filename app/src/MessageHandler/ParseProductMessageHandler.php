<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Comment;
use App\Entity\Image;
use App\Factory\CommentFactory;
use App\Factory\ImageFactory;
use App\Message\AnalyzeCommentsSentimentMessage;
use App\Message\ParseProductMessage;
use App\Message\StoreImagesMessage;
use App\Model\Parser\ProductParseDataProvider;
use App\Repository\ProductRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
readonly class ParseProductMessageHandler
{
    public function __construct(
        private ProductParseDataProvider $productParseDataProvider,
        private ImageFactory $imageFactory,
        private CommentFactory $commentFactory,
        private ProductRepository $productRepository,
        private MessageBusInterface $bus,
    ) {}

    /**
     * @throws \Throwable
     */
    public function __invoke(ParseProductMessage $message): void
    {
        if (!$product = $this->productRepository->find($message->id)) {
            return;
        }
        try {
            $productData = $this->productParseDataProvider->provide($product->getUrl());

            $product->setTitle($productData->title);

            foreach ($productData->images as $imageUrl) {
                if (!$product->getImages()->exists(
                    static fn (mixed $key, Image $image) => $image->getSourceUrl() === $imageUrl
                )) {
                    $image = $this->imageFactory->create($imageUrl);
                    $product->addImage($image);
                }
            }

            foreach ($productData->comments as $content) {
                if (!$product->getComments()->exists(
                    static fn (mixed $key, Comment $comment) => $comment->getContent() === $content
                )) {
                    $comment = $this->commentFactory->create($content);
                    $product->addComment($comment);
                }
            }

            $this->bus->dispatch(new StoreImagesMessage($product->getId()));
            $this->bus->dispatch(new AnalyzeCommentsSentimentMessage($product->getId()));
        } catch (\Throwable $throwable) {
            $product->markStatusAsFailed();
            throw $throwable;
        } finally {
            $this->productRepository->save($product);
        }
    }
}
