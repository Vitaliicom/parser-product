<?php

declare(strict_types=1);

namespace App\Model\Product;

use App\Entity\Product;
use App\Message\ParseProductMessage;
use App\Repository\ProductRepository;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class CreateProductHandler
{
    public function __construct(
        private ProductRepository $productRepository,
        private MessageBusInterface $bus,
    ) {}

    public function handle(string $url): Product
    {
        $product = $this->productRepository->findOneByUrl($url) ?? new Product($url);
        $product->markStatusAsParsing();

        $this->productRepository->save($product);

        $this->bus->dispatch(new ParseProductMessage($product->getId()));

        return $product;
    }
}
