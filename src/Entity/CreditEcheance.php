<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CreditEcheanceRepository")
 * @ORM\Table(name="echeance")
 */
class CreditEcheance
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
     * @ORM\Column(type="date")
     */
    private $paied;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Expence", cascade={"persist", "remove"})
     */
    private $expence;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Credit", inversedBy="creditEcheances")
     * @ORM\JoinColumn(nullable=false)
     */
    private $credit;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

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

    public function getPaied(): ?\DateTimeInterface
    {
        return $this->paied;
    }

    public function setPaied(\DateTimeInterface $paied): self
    {
        $this->paied = $paied;

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

    public function getCredit(): ?Credit
    {
        return $this->credit;
    }

    public function setCredit(?Credit $credit): self
    {
        $this->credit = $credit;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}
