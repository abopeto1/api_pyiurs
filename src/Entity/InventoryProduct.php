<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"inventory_product:read"}}
 * )
 * @ApiFilter(
 *      SearchFilter::Class,
 *      properties={
 *          "product.codebarre"="iexact",
 *          "product.type"="exact",
 *          "inventory"="exact",
 *      }
 * )
 * @ApiFilter(
 *      BooleanFilter::Class,
 *      properties={
 *          "status"
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\InventoryProductRepository")
 * @ORM\Table(
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="product_inventory", columns={"inventory_id","product_id"})
 *  }
 * )
 */
class InventoryProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"inventory"})
     * @Groups({"inventory_product:read"})
     */
    private $id;

    /**
     * @ApiSubresource
     * @ORM\ManyToOne(targetEntity="App\Entity\Inventory", inversedBy="inventoryProducts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"inventory_product:read"})
     */
    private $inventory;

    /**
     * @ApiSubresource
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="inventoryProducts")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"inventory"})
     * @Groups({"inventory_product:read"})
     */
    private $product;

    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({"inventory"})
     * @Groups({"inventory_product:read"})
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Serializer\Groups({"inventory"})
     * @Groups({"inventory_product:read"})
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
