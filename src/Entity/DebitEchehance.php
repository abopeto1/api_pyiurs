<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DebitEchehanceRepository")
 */
class DebitEchehance
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"debit"})
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CashIn", cascade={"persist", "remove"})
     */
    private $cashin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Debit", inversedBy="debitEcheances")
     * @ORM\JoinColumn(nullable=false)
     */
    private $debit;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"debit"})
     */
    private $amount;

    /**
     * @ORM\Column(type="date")
     * @Serializer\Groups({"debit"})
     */
    private $paied;

    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({"debit"})
     */
    private $statut;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCashin(): ?CashIn
    {
        return $this->cashin;
    }

    public function setCashin(?CashIn $cashin): self
    {
        $this->cashin = $cashin;

        return $this;
    }

    public function getDebit(): ?Debit
    {
        return $this->debit;
    }

    public function setDebit(?Debit $debit): self
    {
        $this->debit = $debit;

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

    public function getPaied(): ?\DateTimeInterface
    {
        return $this->paied;
    }

    public function setPaied(\DateTimeInterface $paied): self
    {
        $this->paied = $paied;

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
