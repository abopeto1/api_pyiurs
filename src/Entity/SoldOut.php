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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      subresourceOperations={
 *          "product_sold"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"product:read"}}
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\SoldOutRepository")
 * @ORM\Table(name="solde")
 */
class SoldOut
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"solde","solde_type","one_sold","product_one_sold"})
     * @Groups({"product:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     * @Serializer\Groups({"solde","solde_type","one_sold","product_one_sold"})
     * @Groups({"product:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=11, name="prcent",nullable=true)
     * @Serializer\Groups({"solde","productByCodebarre","one_sold"})
     * @Groups({"product:read"})
     */
    private $percent;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"solde","one_sold"})
     */
    private $created;

    /**
     * @ORM\Column(type="float",nullable=true)
     * @Serializer\Groups({"solde","one_sold","productByCodebarre"})
     * @Groups({"product:read"})
     */
    private $price;

    /**
     * @ApiSubresource
     * @ORM\ManyToOne(targetEntity="App\Entity\SoldType", inversedBy="promotions")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"solde","one_sold","productByCodebarre"})
     * @Groups({"product:read"})
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="sold")
     * @Serializer\Groups({"one_sold","solde"})
     */
    private $products;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Serializer\Groups({"one_sold","solde"})
     */
    private $endDate;

    public function __construct()
    {
        $this->soldOutProducts = new ArrayCollection();
        $this->products = new ArrayCollection();
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

    public function getPercent(): ?string
    {
        return $this->percent;
    }

    public function setPercent(string $percent): self
    {
        $this->percent = $percent;

        return $this;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getType(): ?SoldType
    {
        return $this->type;
    }

    public function setType(?SoldType $type): self
    {
        $this->type = $type;

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
            $product->setSold($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getSold() === $this) {
                $product->setSold(null);
            }
        }

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }
}
