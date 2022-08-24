<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    attributes: ["security" => "is_granted('ROLE_ADMIN')"],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
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
#[UniqueEntity('email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[
        ORM\Column,
        Groups(['user:read'])
    ]
    private ?int $id = null;

    #[
        ORM\Column(length: 180, unique: true),
        Groups(['user:read', 'user:write'])
    ]
    private ?string $email = null;

    #[
        ORM\Column,
        Groups(['user:read', 'user:write'])
    ]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[
        ORM\Column,
        Groups(['user:write'])
    ]
    private ?string $password = null;

    private $passwordHasherFactory;

    #[
        ORM\ManyToMany(targetEntity: Ingredients::class, inversedBy: 'users'),
        Groups(['user:read', 'user:write'])
    ]
    private Collection $allergen;

    #[
        ORM\ManyToMany(targetEntity: Plantypes::class, inversedBy: 'users'),
        Groups(['user:read', 'user:write'])
    ]
    private Collection $plan;

    public function __construct()
    {
        $this->allergen = new ArrayCollection();
        $this->plan = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $plaintextPassword): self
    {
        $this->passwordHasherFactory = new PasswordHasherFactory([
            // auto hasher with default options for the User class (and children)
            self::class => ['algorithm' => 'auto']
        ]);

        $passwordHasher = new UserPasswordHasher($this->passwordHasherFactory);

        // hash the password (based on the password hasher factory config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword(
            $this,
            $plaintextPassword
        );

        $this->password = $hashedPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Ingredients>
     */
    public function getAllergen(): Collection
    {
        return $this->allergen;
    }

    public function addAllergen(Ingredients $allergen): self
    {
        if (!$this->allergen->contains($allergen)) {
            $this->allergen->add($allergen);
        }

        return $this;
    }

    public function removeAllergen(Ingredients $allergen): self
    {
        $this->allergen->removeElement($allergen);

        return $this;
    }

    /**
     * @return Collection<int, Plantypes>
     */
    public function getPlan(): Collection
    {
        return $this->plan;
    }

    public function addPlan(Plantypes $plan): self
    {
        if (!$this->plan->contains($plan)) {
            $this->plan->add($plan);
        }

        return $this;
    }

    public function removePlan(Plantypes $plan): self
    {
        $this->plan->removeElement($plan);

        return $this;
    }
}
