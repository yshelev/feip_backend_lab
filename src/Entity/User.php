<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\UserController;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/user/{id}',
            controller: UserController::class . '::getOneUser',
            security: 'is_granted("IS_AUTHENTICATED_FULLY")'
        ),
        new GetCollection(
            uriTemplate: '/user',
            controller: UserController::class . '::getAllUsers',
            security: 'is_granted("IS_AUTHENTICATED_FULLY")'
        ),
        new Post(
            uriTemplate: '/user',
            controller: UserController::class . '::getAllUsers',
            security: 'is_granted("IS_AUTHENTICATED_FULLY")'
        ),
    ]
)]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 12)]
    private ?string $phoneNumber = null;

    /**
     * @var Collection<int, Request>
     */
    #[ORM\OneToMany(targetEntity: Request::class, mappedBy: 'userId', orphanRemoval: true)]
    private Collection $requests;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $roles = [];

    public function __construct()
    {
        $this->requests = new ArrayCollection();
    }

    public static function create(
        string $phoneNumber
    ) {
        $user = new self(); 
        $user->phoneNumber = $phoneNumber; 

        return $user; 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function eraseCredentials(): void
    {
        return; 
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->phoneNumber;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): static
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->setUser($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getUser() === $this) {
                $request->getUser(null);
            }
        }

        return $this;
    }
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }
}
