<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CreditRepository")
 */
class Credit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"credit","provider","agent"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,unique=true,name="codeCredit")
     * @Serializer\Groups({"credit","agent","provider"})
     */
    private $codeCredit;

    /**
     * @ORM\Column(type="string", length=255, name="typeCredit")
     * @Serializer\Groups({"credit","agent","provider"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=50,name="provider",nullable=true)
     */
    private $fournisseur;

    /**
     * @ORM\Column(type="string", length=200,nullable=true)
     * @Serializer\Groups({"credit","agent","provider"})
     */
    private $motif;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $montant_usd;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $montant_cdf;

    /**
     * @ORM\Column(type="float",nullable=true)
     * @Serializer\Groups({"credit","agent","provider"})
     */
    private $taux;

    /**
     * @ORM\Column(type="float",name="tauxEuro",nullable=true)
     * @Serializer\Groups({"credit","agent","provider"})
     */
    private $tauxEuro;

    /**
     * @ORM\Column(type="string", length=5)
     * @Serializer\Groups({"credit","agent","provider"})
     */
    private $currency;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Serializer\Groups({"credit","provider"})
     */
    private $nbr_echeance;

    /**
     * @ORM\Column(type="datetime",name="dateCreate")
     * @Serializer\Groups({"credit","agent","provider"})
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provider", inversedBy="credits")
     * @Serializer\Groups({"credit"})
     */
    private $provider;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"credit","agent","provider"})
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ClotureMonth", inversedBy="credits")
     */
    private $clotureMonth;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agent", inversedBy="credits")
     */
    private $agent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CreditEcheance", mappedBy="credit")
     */
    private $creditEcheances;

    public function __construct()
    {
        $this->creditEcheances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeCredit(): ?string
    {
        return $this->codeCredit;
    }

    public function setCodeCredit(string $codeCredit): self
    {
        $this->codeCredit = $codeCredit;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFournisseur(): ?string
    {
        return $this->fournisseur;
    }

    public function setFournisseur(string $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

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

    public function getMontantUsd(): ?float
    {
        return $this->montant_usd;
    }

    public function setMontantUsd(float $montant_usd): self
    {
        $this->montant_usd = $montant_usd;

        return $this;
    }

    public function getMontantCdf(): ?float
    {
        return $this->montant_cdf;
    }

    public function setMontantCdf(float $montant_cdf): self
    {
        $this->montant_cdf = $montant_cdf;

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

    public function getTauxEuro(): ?float
    {
        return $this->tauxEuro;
    }

    public function setTauxEuro(float $tauxEuro): self
    {
        $this->tauxEuro = $tauxEuro;

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

    public function getNbrEcheance(): ?int
    {
        return $this->nbr_echeance;
    }

    public function setNbrEcheance(int $nbr_echeance): self
    {
        $this->nbr_echeance = $nbr_echeance;

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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

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

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * @return Collection|CreditEcheance[]
     */
    public function getCreditEcheances(): Collection
    {
        return $this->creditEcheances;
    }

    public function addCreditEcheance(CreditEcheance $creditEcheance): self
    {
        if (!$this->creditEcheances->contains($creditEcheance)) {
            $this->creditEcheances[] = $creditEcheance;
            $creditEcheance->setCredit($this);
        }

        return $this;
    }

    public function removeCreditEcheance(CreditEcheance $creditEcheance): self
    {
        if ($this->creditEcheances->contains($creditEcheance)) {
            $this->creditEcheances->removeElement($creditEcheance);
            // set the owning side to null (unless already changed)
            if ($creditEcheance->getCredit() === $this) {
                $creditEcheance->setCredit(null);
            }
        }

        return $this;
    }
}
