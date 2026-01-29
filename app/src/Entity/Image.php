<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\ImageStatus;
use App\Repository\ProductImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProductImageRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $sourceUrl;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['image:read'])]
    private ?string $url = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $storage = null;

    #[ORM\Column(enumType: ImageStatus::class)]
    private ImageStatus $status = ImageStatus::NEW;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        string $sourceUrl,
    ) {
        $this->sourceUrl = $sourceUrl;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceUrl(): string
    {
        return $this->sourceUrl;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getStorage(): ?string
    {
        return $this->storage;
    }

    public function setStorage(?string $storage): static
    {
        $this->storage = $storage;

        return $this;
    }

    public function getStatus(): ImageStatus
    {
        return $this->status;
    }

    public function isStored(): bool
    {
        return $this->status === ImageStatus::STORED;
    }

    public function markStatusAsStored(): void
    {
        $this->status = ImageStatus::STORED;
    }

    public function markStatusAsFailed(): void
    {
        $this->status = ImageStatus::FAILED;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
