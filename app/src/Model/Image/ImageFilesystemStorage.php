<?php

declare(strict_types=1);

namespace App\Model\Image;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

readonly class ImageFilesystemStorage
{
    private const string PUBLIC_DIR = '/public';
    private const string IMAGES_DIR = '/images/products/';

    public function __construct(
        #[Autowire('%kernel.project_dir%')] private string $projectDir,
        private Filesystem $filesystem,
    ) {}

    public function save(string $fileName, string $content): string
    {
        $targetDir = $this->projectDir . self::PUBLIC_DIR . self::IMAGES_DIR;

        $this->filesystem->mkdir($targetDir);

        $this->filesystem->dumpFile(
            $targetDir . $fileName,
            $content
        );

        return self::IMAGES_DIR . $fileName;
    }
}
