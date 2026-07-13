<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $unit = null;

    /**
     * @var Collection<int, WarehouseOperation>
     */
    #[ORM\OneToMany(targetEntity: WarehouseOperation::class, mappedBy: 'product')]
    private Collection $warehouseOperations;

    public function __construct()
    {
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

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

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
            $warehouseOperation->setProduct($this);
        }

        return $this;
    }

    public function removeWarehouseOperation(WarehouseOperation $warehouseOperation): static
    {
        if ($this->warehouseOperations->removeElement($warehouseOperation)) {
            // set the owning side to null (unless already changed)
            if ($warehouseOperation->getProduct() === $this) {
                $warehouseOperation->setProduct(null);
            }
        }

        return $this;
    }
}
