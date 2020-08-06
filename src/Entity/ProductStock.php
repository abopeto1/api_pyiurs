<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      subresourceOperations={
 *          "product_stock"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"product:read"}}
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ProductStockRepository")
 * @ORM\Table(name="product_store")
 */
class ProductStock
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"productByCodebarre","one_bill","product","product_one_sold","stock_resume","warehouses","one_warehouse"})
     * @Groups({"product:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, name="ProductID",nullable=true)
     */
    private $productId;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"products","stock","productByCodebarre","one_bill","product","list_products","warehouses","product_one_sold","one_warehouse"})
     */
    private $qte;

    /**
     * @ORM\Column(type="integer", name="outQte")
     * @Serializer\Groups({"products","stock","productByCodebarre","one_bill","product","list_products","warehouses","product_one_sold","one_warehouse"})
     */
    private $outQte;

    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({"stock","stock_resume","warehouses","one_warehouse"})
     * @Groups({"product:read"})
     */
    private $available;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"stock","stock_resume"})
     * @Groups({"product:read"})
     */
    private $created;
    
    private $addStock;

    private $delivery_code;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getOutQte(): ?int
    {
        return $this->outQte;
    }

    public function setOutQte(int $outQte): self
    {
        $this->outQte = $outQte;

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
     * @Groups({"product:read"})
     */
    public function getAvailableQte(): ?int
    {
        return $this->qte - $this->outQte;
    }

    public function getAddStock(): ?int
    {
        return $this->addStock;
    }

    public function setAddStock(int $addStock): self
    {
        $this->addStock = $addStock;

        return $this;
    }

    public function getDeliveryCode(): ?string
    {
        return $this->delivery_code;
    }

    public function setDeliveryCode(string $delivery_code): self
    {
        $this->delivery_code = $delivery_code;

        return $this;
    }
}
