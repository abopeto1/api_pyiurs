<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      subresourceOperations={
 *          "inventory_product_inventory"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"inventory_product:read"}}
 *          }
 *      },
 *      normalizationContext={"groups"={"inventory:read"}},
 * )
 * @ApiFilter(
 *      OrderFilter::class,
 *      properties={
 *          "created": "ASC",
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\InventoryRepository")
 */
class Inventory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list_inventories","inventory"})
     * @Groups({"inventory:read","inventory_product:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="date",unique=true)
     * @Serializer\Groups({"list_inventories","inventory"})
     * @Groups({"inventory:read"})
     */
    private $created;

    /**
     * @ApiSubresource
     * @ORM\OneToMany(targetEntity="App\Entity\InventoryProduct", mappedBy="inventory")
     * @Serializer\Groups({"inventory"})
     */
    private $inventoryProducts;

    public function __construct()
    {
        $this->inventoryProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Collection|InventoryProduct[]
     */
    public function getInventoryProducts(): Collection
    {
        return $this->inventoryProducts;
    }

    public function addInventoryProduct(InventoryProduct $inventoryProduct): self
    {
        if (!$this->inventoryProducts->contains($inventoryProduct)) {
            $this->inventoryProducts[] = $inventoryProduct;
            $inventoryProduct->setInventory($this);
        }

        return $this;
    }

    public function removeInventoryProduct(InventoryProduct $inventoryProduct): self
    {
        if ($this->inventoryProducts->contains($inventoryProduct)) {
            $this->inventoryProducts->removeElement($inventoryProduct);
            // set the owning side to null (unless already changed)
            if ($inventoryProduct->getInventory() === $this) {
                $inventoryProduct->setInventory(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"inventory:read"})
     */
    public function getTotal()
    {
        return \count($this->inventoryProducts);
    }

    /**
     * @Groups({"inventory:read"})
     */
    public function getScanned()
    {
        $scanned = $this->getInventoryProducts()->filter(function($inventoryProduct){
            return $inventoryProduct->getStatus();
        });

        return count($scanned);
    }

    /**
     * @Groups({"inventory:read"})
     */
    public function getNotScanned()
    {
        $notScanned = $this->getInventoryProducts()->filter(function ($inventoryProduct) {
            return !$inventoryProduct->getStatus();
        });

        return count($notScanned);
    }
}
