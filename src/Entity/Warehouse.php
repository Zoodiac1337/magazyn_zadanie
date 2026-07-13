<?php

namespace App\Entity;

use App\Repository\WarehouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarehouseRepository::class)]
class Warehouse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'warehouses')]
    private Collection $users;

    /**
     * @var Collection<int, WarehouseOperation>
     */
    #[ORM\OneToMany(targetEntity: WarehouseOperation::class, mappedBy: 'warehouse')]
    private Collection $warehouseOperations;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->warehouseOperations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->users->removeElement($user);

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
            $warehouseOperation->setWarehouse($this);
        }

        return $this;
    }

    public function removeWarehouseOperation(WarehouseOperation $warehouseOperation): static
    {
        if ($this->warehouseOperations->removeElement($warehouseOperation)) {
            // set the owning side to null (unless already changed)
            if ($warehouseOperation->getWarehouse() === $this) {
                $warehouseOperation->setWarehouse(null);
            }
        }

        return $this;
    }
}
