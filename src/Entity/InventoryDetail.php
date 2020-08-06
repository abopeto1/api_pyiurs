<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InventoryDetailRepository")
 * @ORM\Table(name="inventory_details")
 */
class InventoryDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pid;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_inventory;

    /**
     * @ORM\Column(type="boolean")
     */
    private $scanned;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPid(): ?string
    {
        return $this->pid;
    }

    public function setPid(string $pid): self
    {
        $this->pid = $pid;

        return $this;
    }

    public function getIdInventory(): ?int
    {
        return $this->id_inventory;
    }

    public function setIdInventory(int $id_inventory): self
    {
        $this->id_inventory = $id_inventory;

        return $this;
    }

    public function getScanned(): ?bool
    {
        return $this->scanned;
    }

    public function setScanned(bool $scanned): self
    {
        $this->scanned = $scanned;

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
}
