<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CashInRepository")
 */
class CashIn
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"cash_in","clotures"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime",name="dateEnter")
     * @Serializer\Groups({"cash_in","clotures"})
     */
    private $created;

    /**
     * @ORM\Column(type="float",name="montant")
     * @Serializer\Groups({"cash_in","clotures"})
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=5)
     * @Serializer\Groups({"cash_in","clotures"})
     */
    private $currency;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"cash_in"})
     */
    private $operator;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $debiteur;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"cash_in"})
     */
    private $motif;

    /**
     * @ORM\Column(type="text", nullable=true,name="comm")
     * @Serializer\Groups({"cash_in"})
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provider", inversedBy="cashIns")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"cash_in"})
     */
    private $provider;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ClotureDay", inversedBy="cashIns")
     */
    private $clotureDay;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"cash_in","clotures"})
     */
    private $taux;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ClotureMonth", inversedBy="cashins")
     */
    private $clotureMonth;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getOperator(): ?string
    {
        return $this->operator;
    }

    public function setOperator(string $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    public function getDebiteur(): ?int
    {
        return $this->debiteur;
    }

    public function setDebiteur(int $debiteur): self
    {
        $this->debiteur = $debiteur;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getClotureDay(): ?ClotureDay
    {
        return $this->clotureDay;
    }

    public function setClotureDay(?ClotureDay $clotureDay): self
    {
        $this->clotureDay = $clotureDay;

        return $this;
    }

    public function getTaux(): ?float
    {
        return $this->taux;
    }

    public function setTaux(float $taux): self
    {
        $this->taux = $taux;

        return $this;
    }

    public function getClotureMonth(): ?ClotureMonth
    {
        return $this->clotureMonth;
    }

    public function setClotureMonth(?ClotureMonth $clotureMonth): self
    {
        $this->clotureMonth = $clotureMonth;

        return $this;
    }
}
