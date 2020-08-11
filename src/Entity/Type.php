<?php

/*
 * This file is part of the API for Pyiurs Boutique POS.
 *
 * (c) Arnold Bopeto <abopeto1@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Filter\SearchSubresourceFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      subresourceOperations={
 *          "product_type"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"product:read","billDetail:read","inventory_product:read"}}
 *          }
 *      },
 *      normalizationContext={"groups"={"type:read"}},
 *      denormalizationContext={"groups"={"type:write"}}
 * )
 * @ApiFilter(
 *      SearchSubresourceFilter::Class,
 *      properties={
 *          "segment.department": "exact",
 *          "products.delivery": "exact",
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\TypeRepository")
 */
class Type
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"type","products","one_warehouse","stock","customer","inventory","segments","one_sold",
     * "type_stats","warehouses" })
     * @Groups({"type:read","product:read","billDetail:read","inventory_product:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10,name="refType",unique=true)
     * @Groups({"type:read"})
     */

    private $refType;

    /**
     * @ORM\Column(type="string", length=100,unique=true,name="Type")
     * @Serializer\Groups({"type","products","one_warehouse","stock","productByCodebarre","list_products",
     * "sells_reports",
     * "product_one_sold","customer","inventory","segments","one_sold","type_stats","warehouses" })
     * @Groups({"type:read","type:write","product:read","billDetail:read","inventory_product:read"})
     */
    private $name;

    /**
     * ApiSubresource
     * @ORM\ManyToOne(targetEntity="App\Entity\Segment", inversedBy="types")
     * @Serializer\Groups({"products","one_warehouse","sells_reports","one_sold","product_one_sold"})
     * @Groups({"type:read","type:write"})
     */
    private $segment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="type")
     * @Serializer\Groups({"segments","products","type_stats"})
     * @Groups({"type:read"})
     */
    private $products;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefType(): ?string
    {
        return $this->refType;
    }

    public function setRefType(string $refType): self
    {
        $this->refType = $refType;

        return $this;
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

    public function getSegment(): ?Segment
    {
        return $this->segment;
    }

    public function setSegment(?Segment $segment): self
    {
        $this->segment = $segment;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setType($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getType() === $this) {
                $product->setType(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"type:read"})
     */
    public function getDetails()
    {
        $groups = [];
        $groups['total'] = count($this->getProducts());
        $groups['totalNotLoaded'] = count($this->getProducts()->filter(function($product){
            return $product->getMoveStatus() === 0;
        }));
        $groups['totalLoaded'] = count($this->getProducts()->filter(function ($product) {
            return $product->getMoveStatus() === 1;
        }));
        $groups['selled'] = count($this->getProducts()->filter(function ($product) {
            return $product->getMoveStatus() === 1 && !($product->getStock() && $product->getStock()->getAvailable());
        }));
        $groups['totalShop'] = count($this->getProducts()->filter(function ($product) {
            return $product->getMoveStatus() === 1 && ($product->getStock() && $product->getStock()->getAvailable());
        }));
        return $groups;
    }
}
