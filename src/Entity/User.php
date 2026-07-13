<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Warehouse>
     */
    #[ORM\ManyToMany(targetEntity: Warehouse::class, mappedBy: 'users')]
    private Collection $warehouses;

    /**
     * @var Collection<int, WarehouseOperation>
     */
    #[ORM\OneToMany(targetEntity: WarehouseOperation::class, mappedBy: 'user')]
    private Collection $warehouseOperations;

    public function __construct()
    {
        $this->warehouses = new ArrayCollection();
        $this->warehouseOperations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    /**
     * @return Collection<int, Warehouse>
     */
    public function getWarehouses(): Collection
    {
        return $this->warehouses;
    }

    public function addWarehouse(Warehouse $warehouse): static
    {
        if (!$this->warehouses->contains($warehouse)) {
            $this->warehouses->add($warehouse);
            $warehouse->addUser($this);
        }

        return $this;
    }

    public function removeWarehouse(Warehouse $warehouse): static
    {
        if ($this->warehouses->removeElement($warehouse)) {
            $warehouse->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, WarehouseOperation>
     */
    public function getWarehouseOperations(): Collection
    {
        return $this->warehouseOperations;
    }

    public function addWarehouseOperation(WarehouseOperation $warehouseOperation): static
    {
        if (!$this->warehouseOperations->contains($warehouseOperation)) {
            $this->warehouseOperations->add($warehouseOperation);
            $warehouseOperation->setUser($this);
        }

        return $this;
    }

    public function removeWarehouseOperation(WarehouseOperation $warehouseOperation): static
    {
        if ($this->warehouseOperations->removeElement($warehouseOperation)) {
            // set the owning side to null (unless already changed)
            if ($warehouseOperation->getUser() === $this) {
                $warehouseOperation->setUser(null);
            }
        }

        return $this;
    }
}