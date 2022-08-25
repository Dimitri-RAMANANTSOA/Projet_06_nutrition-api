<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecipesRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Constraints;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RecipesRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    paginationItemsPerPage: 10,
    paginationMaximumItemsPerPage: 100,
    paginationClientItemsPerPage: true,
    normalizationContext: ['groups' => ['recipes:read']],
    denormalizationContext: ['groups' => ['recipes:write']],
    collectionOperations: [
        'get',
        'post' => ["security" => "is_granted('ROLE_ADMIN')"]
    ],
    itemOperations: [
        'get',
        'patch' => ["security" => "is_granted('ROLE_ADMIN')"],
        'delete' => ["security" => "is_granted('ROLE_ADMIN')"]
    ],
)]
#[UniqueEntity('title')]
/** Recipe */
class Recipes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[
        ORM\Column,
        Groups(['recipes:read', 'feedback:read'])
    ]
    /** Recipe ID */
    private ?int $id = null;

    #[
        ORM\Column(length: 255),
        Groups(['recipes:read', 'recipes:write', 'feedback:read']),
        Constraints\NotBlank,
        Constraints\Length(min: 5, max: 100)
    ]
    /** Recipe title */
    private ?string $title = null;

    #[
        ORM\Column(type: Types::TEXT),
        Groups(['recipes:read', 'recipes:write']),
        Constraints\NotBlank,
        Constraints\Length(min: 5, max: 255)
    ]
    /** Recipe description */
    private ?string $description = null;

    #[
        ORM\Column(length: 255),
        Groups(['recipes:read', 'recipes:write']),
        Constraints\NotBlank,
        Constraints\Length(min: 1, max: 10)
    ]
    /** Recipe setupTime in minutes */
    private ?string $setupTime = null;

    #[
        ORM\Column(length: 255),
        Groups(['recipes:read', 'recipes:write']),
        Constraints\NotBlank,
        Constraints\Length(min: 1, max: 10)
    ]
    /** Recipe restTime in minutes */
    private ?string $restTime = null;

    #[
        ORM\Column(length: 255),
        Groups(['recipes:read', 'recipes:write']),
        Constraints\NotBlank,
        Constraints\Length(min: 5, max: 255)
    ]
    /** Recipe detailed steps */
    private ?string $steps = null;

    #[
        ORM\ManyToMany(targetEntity: Ingredients::class, inversedBy: 'recipes'),
        Groups(['recipes:read', 'recipes:write']),
        Constraints\NotBlank
    ]
    /** Recipe ingredients list (need array of ingredients URI) */
    private Collection $ingredients;

    #[
        ORM\ManyToMany(targetEntity: Plantypes::class, inversedBy: 'recipes'),
        Groups(['recipes:read', 'recipes:write']),
        Constraints\NotBlank
    ]
    /** Recipe plan type (need array of plantype URI) */
    private Collection $plantype;

    #[
        ORM\Column(type: Types::DATETIME_MUTABLE),
        Groups(['recipes:read'])
    ]
    /** Recipe created date (this date is set up automatically at creation) */
    private ?\DateTimeInterface $createdAt = null;

    #[
        ORM\Column(type: Types::DATETIME_MUTABLE),
        Groups(['recipes:read'])
    ]
    /** Recipe updated date (this date is updated automatically) */
    private ?\DateTimeInterface $updatedAt = null;

    #[
        ORM\Column,
        Groups(['recipes:read', 'recipes:write']),
        Constraints\Range(min: 0, max: 1)
    ]
    /** Recipe Public status : 0 (by default) = only visible by authenticated users, 1 = visible by all) */
    private ?bool $isPublic = false;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Feedback::class)]
    /** Recipe feedbacks */
    private Collection $feedback;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->ingredients = new ArrayCollection();
        $this->plantype = new ArrayCollection();
        $this->feedback = new ArrayCollection();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateModifiedDatetime() {
        // update the modified time
        $this->updatedAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSetupTime(): ?string
    {
        return $this->setupTime;
    }

    public function setSetupTime(string $setupTime): self
    {
        $this->setupTime = $setupTime;

        return $this;
    }

    public function getRestTime(): ?string
    {
        return $this->restTime;
    }

    public function setRestTime(string $restTime): self
    {
        $this->restTime = $restTime;

        return $this;
    }

    public function getSteps(): ?string
    {
        return $this->steps;
    }

    public function setSteps(string $steps): self
    {
        $this->steps = $steps;

        return $this;
    }

    /**
     * @return Collection<int, Ingredients>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredients $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(Ingredients $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    /**
     * @return Collection<int, Plantypes>
     */
    public function getPlantype(): Collection
    {
        return $this->plantype;
    }

    public function addPlantype(Plantypes $plantype): self
    {
        if (!$this->plantype->contains($plantype)) {
            $this->plantype->add($plantype);
        }

        return $this;
    }

    public function removePlantype(Plantypes $plantype): self
    {
        $this->plantype->removeElement($plantype);

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): self
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback->add($feedback);
            $feedback->setRecipe($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getRecipe() === $this) {
                $feedback->setRecipe(null);
            }
        }

        return $this;
    }
}