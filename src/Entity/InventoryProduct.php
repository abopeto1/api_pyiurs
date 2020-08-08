<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\InventoryProductRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="product_inventory", columns={"inventory_id","product_id"})})
 */
class InventoryProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"inventory"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Inventory", inversedBy="inventoryProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $inventory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="inventoryProducts")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"inventory"})
     */
    private $product;

    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({"inventory"})
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Serializer\Groups({"inventory"})
     */
    private $updated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInventory(): ?Inventory
    {
        return $this->inventory;
    }

    public function setInventory(?Inventory $inventory): self
    {
        $this->inventory = $inventory;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }
}
