<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExpenceRepository")
 * @ORM\Table(name="depense")
 */
class Expence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"expence","provider","clotures","expenceCompteCategory","order","order_echeance"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,name="Transact_ID")
     * @Serializer\Groups({"expence","expenceCompteCategory"})
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255,name="detail_trans")
     * @Serializer\Groups({"expence","expenceCompteCategory"})
     */
    private $motif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Groups({"expence"})
     */
    private $numFact;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Groups({"expence","clotures","expenceCompteCategory"})
     */
    private $taux;

    /**
     * @ORM\Column(type="string", length=10)
     * @Serializer\Groups({"expence","clotures","expenceCompteCategory","order_echeance"})
     */
    private $currency;

    /**
     * @ORM\Column(type="datetime",name="T_date")
     * @Serializer\Groups({"expence","expenceCompteCategory"})
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Serializer\Groups({"expence","clotures","expenceCompteCategory"})
     */
    private $periode;

    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({"expence","expenceCompteCategory"})
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provider", inversedBy="expences")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"expence"})
     */
    private $provider;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"expence","clotures","expenceCompteCategory","order_echeance"})
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ExpenceCompte", inversedBy="expences")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"expence"})
     */
    private $expenceCompte;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ClotureDay", inversedBy="expences")
     */
    private $clotureDay;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ClotureMonth", inversedBy="expences")
     */
    private $clotureMonth;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="expences")
     * @Serializer\Groups({"expence"})
     */
    private $operator;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @Serializer\Groups({"expence"})
     */
    private $validated;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\OrderEcheance", mappedBy="expence", cascade={"persist", "remove"})
     */
    private $orderEcheance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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

    public function getNumFact(): ?string
    {
        return $this->numFact;
    }

    public function setNumFact(?string $numFact): self
    {
        $this->numFact = $numFact;

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

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

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

    public function getPeriode(): ?string
    {
        return $this->periode;
    }

    public function setPeriode(?string $periode): self
    {
        $this->periode = $periode;

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

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getExpenceCompte(): ?ExpenceCompte
    {
        return $this->expenceCompte;
    }

    public function setExpenceCompte(?ExpenceCompte $expenceCompte): self
    {
        $this->expenceCompte = $expenceCompte;

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

    public function getClotureMonth(): ?ClotureMonth
    {
        return $this->clotureMonth;
    }

    public function setClotureMonth(?ClotureMonth $clotureMonth): self
    {
        $this->clotureMonth = $clotureMonth;

        return $this;
    }

    public function getOperator(): ?User
    {
        return $this->operator;
    }

    public function setOperator(?User $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    public function getValidated(): ?\DateTimeInterface
    {
        return $this->validated;
    }

    public function setValidated(\DateTimeInterface $validated): self
    {
        $this->validated = $validated;

        return $this;
    }

    public function getOrderEcheance(): ?OrderEcheance
    {
        return $this->orderEcheance;
    }

    public function setOrderEcheance(?OrderEcheance $orderEcheance): self
    {
        $this->orderEcheance = $orderEcheance;

        // set (or unset) the owning side of the relation if necessary
        $newExpence = null === $orderEcheance ? null : $this;
        if ($orderEcheance->getExpence() !== $newExpence) {
            $orderEcheance->setExpence($newExpence);
        }

        return $this;
    }
}
