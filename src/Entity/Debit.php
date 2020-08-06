<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DebitRepository")
 */
class Debit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"debit","provider"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provider", inversedBy="debits")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"debit"})
     */
    private $provider;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"debit","provider"})
     */
    private $motif;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"debit","provider"})
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=5)
     * @Serializer\Groups({"debit","provider"})
     */
    private $currency;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Groups({"debit","provider"})
     */
    private $taux;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"debit","provider"})
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DebitEchehance", mappedBy="debit")
     * @Serializer\Groups({"debit"})
     */
    private $debitEcheances;

    public function __construct()
    {
        $this->debitEcheances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

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

    public function getTaux(): ?float
    {
        return $this->taux;
    }

    public function setTaux(?float $taux): self
    {
        $this->taux = $taux;

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
     * @return Collection|DebitEchehance[]
     */
    public function getDebitEcheances(): Collection
    {
        return $this->debitEcheances;
    }

    public function addDebitEcheance(DebitEchehance $debitEcheance): self
    {
        if (!$this->debitEcheances->contains($debitEcheance)) {
            $this->debitEcheances[] = $debitEcheance;
            $debitEcheance->setDebit($this);
        }

        return $this;
    }

    public function removeDebitEcheance(DebitEchehance $debitEcheance): self
    {
        if ($this->debitEcheances->contains($debitEcheance)) {
            $this->debitEcheances->removeElement($debitEcheance);
            // set the owning side to null (unless already changed)
            if ($debitEcheance->getDebit() === $this) {
                $debitEcheance->setDebit(null);
            }
        }

        return $this;
    }
}
