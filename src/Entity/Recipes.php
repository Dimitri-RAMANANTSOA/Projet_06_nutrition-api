<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecipesRepository;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity(repositoryClass: RecipesRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Recipes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $setupTime = null;

    #[ORM\Column(length: 255)]
    private ?string $restTime = null;

    #[ORM\Column(length: 255)]
    private ?string $steps = null;

    #[ORM\ManyToMany(targetEntity: Ingredients::class, inversedBy: 'recipes')]
    private Collection $ingredients;

    #[ORM\ManyToMany(targetEntity: Plantypes::class, inversedBy: 'recipes')]
    private Collection $plantype;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column]
    private ?bool $isPublic = false;

    public function __construct()
    {
        //$this->createdAt = new \DateTime('now');
        $this->ingredients = new ArrayCollection();
        $this->plantype = new ArrayCollection();
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
}
