<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClotureDayRepository")
 * @ORM\Table(name="balence")
 */
class ClotureDay
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"clotures","report_cloture","list_cloture"})
     */
    private $id;

    /**
     * @ORM\Column(type="date",name="C_date",unique=true)
     * @Serializer\Groups({"clotures","report_cloture","list_cloture"})
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bill", mappedBy="clotureDay")
     * @Serializer\Groups({"clotures","report_cloture"})
     */
    private $bills;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Expence", mappedBy="clotureDay")
     * @Serializer\Groups({"clotures","report_cloture"})
     */
    private $expences;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Groups({"report_cloture"})
     */
    private $theoric_cash;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CashIn", mappedBy="clotureDay")
     * @Serializer\Groups({"clotures","report_cloture"})
     */
    private $cashIns;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Groups({"clotures","report_cloture"})
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="clotureDays")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"clotures","report_cloture"})
     */
    private $operator;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Service", mappedBy="cloture_day")
     */
    private $services;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $theoric_cash_cdf;

    public function __construct()
    {
        $this->bills = new ArrayCollection();
        $this->expences = new ArrayCollection();
        $this->cashIns = new ArrayCollection();
        $this->services = new ArrayCollection();
    }

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

    /**
     * @return Collection|Bill[]
     */
    public function getBills(): Collection
    {
        return $this->bills;
    }

    public function addBill(Bill $bill): self
    {
        if (!$this->bills->contains($bill)) {
            $this->bills[] = $bill;
            $bill->setClotureDay($this);
        }

        return $this;
    }

    public function removeBill(Bill $bill): self
    {
        if ($this->bills->contains($bill)) {
            $this->bills->removeElement($bill);
            // set the owning side to null (unless already changed)
            if ($bill->getClotureDay() === $this) {
                $bill->setClotureDay(null);
            }
        }

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
            $expence->setClotureDay($this);
        }

        return $this;
    }

    public function removeExpence(Expence $expence): self
    {
        if ($this->expences->contains($expence)) {
            $this->expences->removeElement($expence);
            // set the owning side to null (unless already changed)
            if ($expence->getClotureDay() === $this) {
                $expence->setClotureDay(null);
            }
        }

        return $this;
    }


    public function getTheoricCash(): ?float
    {
        return $this->theoric_cash;
    }

    public function setTheoricCash(float $theoric_cash): self
    {
        $this->theoric_cash = $theoric_cash;

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
            $cashIn->setClotureDay($this);
        }

        return $this;
    }

    public function removeCashIn(CashIn $cashIn): self
    {
        if ($this->cashIns->contains($cashIn)) {
            $this->cashIns->removeElement($cashIn);
            // set the owning side to null (unless already changed)
            if ($cashIn->getClotureDay() === $this) {
                $cashIn->setClotureDay(null);
            }
        }

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

    public function getOperator(): ?User
    {
        return $this->operator;
    }

    public function setOperator(?User $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setClotureDay($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->contains($service)) {
            $this->services->removeElement($service);
            // set the owning side to null (unless already changed)
            if ($service->getClotureDay() === $this) {
                $service->setClotureDay(null);
            }
        }

        return $this;
    }

    public function getTheoricCashCdf(): ?float
    {
        return $this->theoric_cash_cdf;
    }

    public function setTheoricCashCdf(?float $theoric_cash_cdf): self
    {
        $this->theoric_cash_cdf = $theoric_cash_cdf;

        return $this;
    }
}
