<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\IngredientsRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IngredientsRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['ingredients:read']],
    denormalizationContext: ['groups' => ['ingredients:write']],
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
class Ingredients
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ingredients:read', 'ingredients:write', 'recipes:read','user:read'])]
    private ?int $id = null;

    #[
        ORM\Column(length: 255),
        Groups(['ingredients:read', 'ingredients:write', 'recipes:read','user:read'])
    ]
    private ?string $name = null;

    #[
        ORM\Column,
        Groups(['ingredients:read', 'ingredients:write', 'recipes:read','user:read'])
    ]
    private ?bool $isAllergen = null;

    #[
        ORM\ManyToMany(targetEntity: Recipes::class, mappedBy: 'ingredients'),
        Groups(['ingredients:read', 'ingredients:write'])
    ]
    private Collection $recipes;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'allergen')]
    private Collection $users;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function isIsAllergen(): ?bool
    {
        return $this->isAllergen;
    }

    public function setIsAllergen(bool $isAllergen): self
    {
        $this->isAllergen = $isAllergen;

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
            $recipe->addIngredient($this);
        }

        return $this;
    }

    public function removeRecipe(Recipes $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            $recipe->removeIngredient($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addAllergen($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeAllergen($this);
        }

        return $this;
    }
}
