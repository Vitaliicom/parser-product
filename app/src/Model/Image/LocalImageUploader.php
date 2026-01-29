<?php

declare(strict_types=1);

namespace App\Model\Image;

use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsAlias(id: ImageUploaderInterface::class)]
readonly class LocalImageUploader implements ImageUploaderInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private ImageFilesystemStorage $imageFilesystemStorage,
    ) {}

    public function upload(string $url): string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid image URL');
        }

        try {
            $response = $this->httpClient->request('GET', $url, [
                'timeout' => 10,
            ]);

            $contentType = $response->getHeaders(false)['content-type'][0] ?? '';

            if (!str_starts_with($contentType, 'image/')) {
                throw new \RuntimeException('Downloaded file is not an image');
            }

            $content = $response->getContent();
        } catch (\Throwable $throwable) {
            throw new \RuntimeException(
                sprintf(
                    'Failed to download file from "%s"',
                    $url,
                ),
                previous: $throwable
            );
        }

        $extension = pathinfo(
            parse_url($url, PHP_URL_PATH),
            PATHINFO_EXTENSION
        ) ?: 'jpg';
        $fileName = uniqid('img_', true) . '.' . $extension;

        return $this->imageFilesystemStorage->save($fileName, $content);
    }
}
