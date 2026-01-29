<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\Product\ParseProductRequest;
use App\Entity\Product;
use App\Model\Product\CreateProductHandler;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/products')]
final class ProductController extends AbstractController
{
    #[Route('/parse', methods: [Request::METHOD_POST], format: 'json')]
    #[OA\Post(
        description: 'Parse product page by URL',
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['url'],
            properties: [
                new OA\Property(
                    property: 'url',
                    type: 'string',
                    format: 'uri',
                    example: 'https://example.ua/product/8956'
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Product parsed',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 1),
            ]
        )
    )]
    public function parse(
        #[MapRequestPayload] ParseProductRequest $parseProductRequest,
        CreateProductHandler $handler,
    ): JsonResponse {
        $product = $handler->handle($parseProductRequest->url);
        return $this->json([
            'id' => $product->getId(),
        ]);
    }

    #[Route('/{id<\d+>}', methods: [Request::METHOD_GET], format: 'json')]
    #[OA\Get(
        description: 'Get product by ID'
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
    )]
    #[OA\Response(
        response: 200,
        description: 'Product details'
    )]
    #[OA\Response(response: 404, description: 'Product not found')]
    public function show(Product $product): JsonResponse
    {
        return $this->json(
            $product,
            context: ['groups' => [
                'product:read',
                'image:read',
                'comment:read',
            ]]
        );
    }
}
