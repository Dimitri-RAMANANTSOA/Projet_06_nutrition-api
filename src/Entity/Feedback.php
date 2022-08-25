<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FeedbackRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Constraints;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
#[ApiResource(
    paginationItemsPerPage: 10,
    paginationMaximumItemsPerPage: 100,
    paginationClientItemsPerPage: true,
    normalizationContext: ['groups' => ['feedback:read']],
    denormalizationContext: ['groups' => ['feedback:write']],
    collectionOperations: [
        'get',
        'post' => ["security" => "is_granted('ROLE_USER')"]
    ],
    itemOperations: [
        'get',
        'patch',
        'delete' => ["security" => "is_granted('ROLE_ADMIN')"]
    ],
)]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['feedback:read', 'recipes:read', 'user:read'])]
    private ?int $id = null;

    #[
        ORM\Column(type: Types::TEXT),
        Groups(['feedback:read', 'feedback:write', 'recipes:read', 'user:read']),
        Constraints\NotBlank,
        Constraints\Length(min: 5, max: 1000)
    ]
    private ?string $text = null;

    #[
        ORM\Column(type: Types::DATETIME_MUTABLE),
        Groups(['feedback:read', 'recipes:read', 'user:read'])
    ]
    private ?\DateTimeInterface $createdAt = null;

    #[
        ORM\ManyToOne(inversedBy: 'feedback'),
        Groups(['feedback:read', 'feedback:write', 'recipes:read', 'user:read']),
        Constraints\NotBlank
    ]
    private ?Recipes $recipe = null;

    #[
        ORM\ManyToOne(inversedBy: 'feedback'),
        Groups(['feedback:read', 'recipes:read', 'user:read'])
    ]
    private ?User $owner = null;

    #[
        ORM\Column(type: Types::SMALLINT, nullable: true),
        Groups(['feedback:read', 'feedback:write', 'recipes:read', 'user:read']),
        Constraints\NotBlank,
        Constraints\Range(min: 0, max: 5)
    ]
    private ?int $rating = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRecipe(): ?Recipes
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipes $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
