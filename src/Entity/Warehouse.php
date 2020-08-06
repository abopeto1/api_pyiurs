<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\WarehouseRepository")
 */
class Warehouse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"warehouses","products","one_warehouse"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25,name="ref",unique=true)
     * @Serializer\Groups({"one_warehouse"})
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255,name="wh_name")
     * @Serializer\Groups({"warehouses","products","one_warehouse"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Groups({"warehouses","one_warehouse"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime",name="date")
     * @Serializer\Groups({"warehouses","one_warehouse"})
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="warehouse")
     * @Serializer\Groups({"one_warehouse"})
     */
    private $products;

    /**
     * @Serializer\Groups({"warehouses"})
     */
    private $notLoadedQte = 0;

    /**
     * @Serializer\Groups({"warehouses"})
     */
    private $notLoadedValue = 0;

    /**
     * @Serializer\Groups({"warehouses"})
     */
    private $notLoadedSellValue = 0;

    /**
     * @Serializer\Groups({"warehouses"})
     */
    private $loadedQte = 0;

    /**
     * @Serializer\Groups({"warehouses"})
     */
    private $loadedValue = 0;

    /**
     * @Serializer\Groups({"warehouses"})
     */
    private $loadedSellValue = 0;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
            $product->setWarehouse($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getWarehouse() === $this) {
                $product->setWarehouse(null);
            }
        }

        return $this;
    }

    public function getNotLoadedQte(): ?int
    {
        return $this->notLoadedQte;
    }

    public function setNotLoadedQte(int $notLoadedQte): self
    {
        $this->notLoadedQte = $notLoadedQte;

        return $this;
    }

    public function getNotLoadedValue(): ?int
    {
        return $this->notLoadedValue;
    }

    public function setNotLoadedValue(int $notLoadedValue): self
    {
        $this->notLoadedValue = $notLoadedValue;

        return $this;
    }

    public function getNotLoadedSellValue(): ?int
    {
        return $this->notLoadedSellValue;
    }

    public function setNotLoadedSellValue(int $notLoadedSellValue): self
    {
        $this->notLoadedSellValue = $notLoadedSellValue;

        return $this;
    }

    public function getLoadedQte(): ?int
    {
        return $this->loadedQte;
    }

    public function setLoadedQte(int $loadedQte): self
    {
        $this->loadedQte = $loadedQte;

        return $this;
    }

    public function getLoadedValue(): ?int
    {
        return $this->loadedValue;
    }

    public function setLoadedValue(int $loadedValue): self
    {
        $this->loadedValue = $loadedValue;

        return $this;
    }

    public function getLoadedSellValue(): ?int
    {
        return $this->loadedSellValue;
    }

    public function setLoadedSellValue(int $loadedSellValue): self
    {
        $this->loadedSellValue = $loadedSellValue;

        return $this;
    }
}
