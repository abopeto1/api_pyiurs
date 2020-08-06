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
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *      subresourceOperations={
 *          "product_brand"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"product:read"}}
 *          }
 *      },
 *      normalizationContext={"groups"={"brand:read"}},
 *      denormalizationContext={"groups"={"brand:write"}}
 * )
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "products.billDetails.bill.customer": "exact"
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\BrandRepository")
 * @UniqueEntity("name")
 */
class Brand
{
    /**
     * @var int the id of the brand
     * 
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"brand:read","product:read"})
     */
    private $id;

    /**
     * @var string the name of the brand
     * 
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     * @Groups({"brand:read","brand:write","product:read"})
     */
    private $name;

    /**
     * @var string the url of the logo
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"brand:read"})
     */
    private $logo;

    /**
     * @var string the brand slogan
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"brand:read","brand:write"})
     */
    private $slogan;

    /**
     * @var array Collection of All Brand's Products
     * 
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="brand")
     */
    private $products;

    public function __construct()
    {
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(?string $slogan): self
    {
        $this->slogan = $slogan;

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
            $product->setBrand($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getBrand() === $this) {
                $product->setBrand(null);
            }
        }

        return $this;
    }
}
