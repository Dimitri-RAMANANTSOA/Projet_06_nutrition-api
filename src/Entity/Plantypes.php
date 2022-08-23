<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlantypesRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlantypesRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['plantypes:read']],
    denormalizationContext: ['groups' => ['plantypes:write']],
    paginationItemsPerPage: 10,
    paginationMaximumItemsPerPage: 100,
    paginationClientItemsPerPage: true,
    collectionOperations: [
        'get',
        'post'
    ],
    itemOperations: [
        'get',
        'patch',
        'delete'
    ],
)]
class Plantypes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['plantypes:read', 'plantypes:write', 'recipes:read'])]
    private ?int $id = null;

    #[
        ORM\Column(length: 255),
        Groups(['plantypes:read', 'plantypes:write', 'recipes:read'])
    ]
    private ?string $name = null;

    #[
        ORM\ManyToMany(targetEntity: Recipes::class, mappedBy: 'plantype'),
        Groups(['plantypes:read', 'plantypes:write'])
    ]
    private Collection $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Recipes>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipes $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->addPlantype($this);
        }

        return $this;
    }

    public function removeRecipe(Recipes $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            $recipe->removePlantype($this);
        }

        return $this;
    }
}
