<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderEcheanceRepository")
 */
class OrderEcheance
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"order","order_echeance"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Serializer\Groups({"order","order_echeance"})
     */
    private $paied;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"order","order_echeance"})
     */
    private $amount;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Expence", inversedBy="orderEcheance", cascade={"persist", "remove"})
     * @Serializer\Groups({"order","order_echeance"})
     */
    private $expence;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="orderEcheances")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"order_echeance"})
     */
    private $the_order;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaied(): ?\DateTimeInterface
    {
        return $this->paied;
    }

    public function setPaied(\DateTimeInterface $paied): self
    {
        $this->paied = $paied;

        return $this;
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

    public function getExpence(): ?Expence
    {
        return $this->expence;
    }

    public function setExpence(?Expence $expence): self
    {
        $this->expence = $expence;

        return $this;
    }

    public function getTheOrder(): ?Order
    {
        return $this->the_order;
    }

    public function setTheOrder(?Order $the_order): self
    {
        $this->the_order = $the_order;

        return $this;
    }
}
