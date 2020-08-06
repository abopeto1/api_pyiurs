<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProviderRepository")
 */
class Provider
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"provider","cash_in","credit","expence","debit"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     * @Serializer\Groups({"provider"})
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"provider","cash_in","expence","credit","debit"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=10,nullable=true,name="number")
     * @Serializer\Groups({"provider"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Serializer\Groups({"provider"})
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Serializer\Groups({"provider"})
     */
    private $adress;

    /**
     * @ORM\Column(type="datetime",name="dateSave")
     * @Serializer\Groups({"provider"})
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"provider"})
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Expence", mappedBy="provider")
     * @Serializer\Groups({"provider"})
     */
    private $expences;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CashIn", mappedBy="provider")
     */
    private $cashIns;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Credit", mappedBy="provider")
     * @Serializer\Groups({"provider"})
     */
    private $credits;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Debit", mappedBy="provider")
     * @Serializer\Groups({"provider"})
     */
    private $debits;

    public function __construct()
    {
        $this->cashIns = new ArrayCollection();
        $this->credits = new ArrayCollection();
        $this->debits = new ArrayCollection();
    }


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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Expence[]
     */
    public function getExpences(): Collection
    {
        return $this->expences;
    }

    public function addExpence(Expence $expence): self
    {
        if (!$this->expences->contains($expence)) {
            $this->expences[] = $expence;
            $expence->setProvider($this);
        }

        return $this;
    }

    public function removeExpence(Expence $expence): self
    {
        if ($this->expences->contains($expence)) {
            $this->expences->removeElement($expence);
            // set the owning side to null (unless already changed)
            if ($expence->getProvider() === $this) {
                $expence->setProvider(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CashIn[]
     */
    public function getCashIns(): Collection
    {
        return $this->cashIns;
    }

    public function addCashIn(CashIn $cashIn): self
    {
        if (!$this->cashIns->contains($cashIn)) {
            $this->cashIns[] = $cashIn;
            $cashIn->setProvider($this);
        }

        return $this;
    }

    public function removeCashIn(CashIn $cashIn): self
    {
        if ($this->cashIns->contains($cashIn)) {
            $this->cashIns->removeElement($cashIn);
            // set the owning side to null (unless already changed)
            if ($cashIn->getProvider() === $this) {
                $cashIn->setProvider(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Credit[]
     */
    public function getCredits(): Collection
    {
        return $this->credits;
    }

    public function addCredit(Credit $credit): self
    {
        if (!$this->credits->contains($credit)) {
            $this->credits[] = $credit;
            $credit->setProvider($this);
        }

        return $this;
    }

    public function removeCredit(Credit $credit): self
    {
        if ($this->credits->contains($credit)) {
            $this->credits->removeElement($credit);
            // set the owning side to null (unless already changed)
            if ($credit->getProvider() === $this) {
                $credit->setProvider(null);
            }
        }

        return $this;
    }

    public function getActivity(): ?string
    {
        return $this->activity;
    }

    public function setActivity(?string $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * @return Collection|Debit[]
     */
    public function getDebits(): Collection
    {
        return $this->debits;
    }

    public function addDebit(Debit $debit): self
    {
        if (!$this->debits->contains($debit)) {
            $this->debits[] = $debit;
            $debit->setProvider($this);
        }

        return $this;
    }

    public function removeDebit(Debit $debit): self
    {
        if ($this->debits->contains($debit)) {
            $this->debits->removeElement($debit);
            // set the owning side to null (unless already changed)
            if ($debit->getProvider() === $this) {
                $debit->setProvider(null);
            }
        }

        return $this;
    }
}
