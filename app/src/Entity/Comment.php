<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\CommentStatus;
use App\Enum\Sentiment;
use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['comment:read'])]
    private ?string $content = null;

    #[ORM\Column(enumType: Sentiment::class)]
    #[Groups(['comment:read'])]
    private Sentiment $sentiment = Sentiment::UNKNOWN;

    #[ORM\Column(enumType: CommentStatus::class)]
    private CommentStatus $status = CommentStatus::NEW;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        string $content,
    ) {
        $this->content = $content;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getSentiment(): Sentiment
    {
        return $this->sentiment;
    }

    public function markPositive(): void
    {
        $this->sentiment = Sentiment::POSITIVE;
    }

    public function markNeutral(): void
    {
        $this->sentiment = Sentiment::NEUTRAL;
    }

    public function markNegative(): void
    {
        $this->sentiment = Sentiment::NEGATIVE;
    }

    public function getStatus(): CommentStatus
    {
        return $this->status;
    }

    public function isAnalyzed(): bool
    {
        return $this->status === CommentStatus::ANALYZED;
    }

    public function markStatusAsAnalyzed(): void
    {
        $this->status = CommentStatus::ANALYZED;
    }

    public function markStatusAsFailed(): void
    {
        $this->status = CommentStatus::FAILED;
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
