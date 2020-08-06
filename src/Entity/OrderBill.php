<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderBillRepository")
 */
class OrderBill
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $taux;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $currency;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pdf_url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $store;

    /**
     * @ORM\Column(type="integer")
     */
    private $total_products;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="orderBills")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orders;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getTaux(): ?float
    {
        return $this->taux;
    }

    public function setTaux(?float $taux): self
    {
        $this->taux = $taux;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getPdfUrl(): ?string
    {
        return $this->pdf_url;
    }

    public function setPdfUrl(?string $pdf_url): self
    {
        $this->pdf_url = $pdf_url;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getStore(): ?string
    {
        return $this->store;
    }

    public function setStore(string $store): self
    {
        $this->store = $store;

        return $this;
    }

    public function getTotalProducts(): ?int
    {
        return $this->total_products;
    }

    public function setTotalProducts(int $total_products): self
    {
        $this->total_products = $total_products;

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

    public function getOrders(): ?Order
    {
        return $this->orders;
    }

    public function setOrders(?Order $orders): self
    {
        $this->orders = $orders;

        return $this;
    }
}
