<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      subresourceOperations={
 *          "product_product_sample"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"product:read"}},
 *          }
 *      },
 *      normalizationContext={"groups"={"product_sample:read"}},
 *      denormalizationContext={"groups"={"product_sample:write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ProductSampleRepository")
 */
class ProductSample
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"product_sample:read","product:read"})
     */
    private $id;

    /**
     * @ApiSubresource
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="samples")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"product_sample:read","product_sample:write"})
     */
    private $product;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"product_sample:read","product:read"})
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"product_sample:read","product:read"})
     */
    private $ended;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"product_sample:read","product:read"})
     */
    private $available;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getEnded(): ?\DateTimeInterface
    {
        return $this->ended;
    }

    public function setEnded(?\DateTimeInterface $ended): self
    {
        $this->ended = $ended;

        return $this;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }
}
