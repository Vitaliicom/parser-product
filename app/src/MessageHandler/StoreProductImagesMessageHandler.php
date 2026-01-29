<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\StoreImagesMessage;
use App\Model\Image\ImageUploaderInterface;
use App\Repository\ProductRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class StoreProductImagesMessageHandler
{
    public function __construct(
        private ProductRepository $productRepository,
        private ImageUploaderInterface $imageUploader,
    ) {}

    public function __invoke(StoreImagesMessage $message): void
    {
        if (!$product = $this->productRepository->find($message->productId)) {
            return;
        }
        foreach ($product->getImages() as $image) {
            if (!$image->isStored()) {
                try {
                    $url = $this->imageUploader->upload($image->getSourceUrl());
                    $image->setUrl($url);
                    $image->markStatusAsStored();
                } catch (\Throwable) {
                    $image->markStatusAsFailed();
                }
            }
        }

        $this->productRepository->save($product);
    }
}
