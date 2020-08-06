<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClotureMonthRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="cloture_month", columns={"month","year"})})
 */
class ClotureMonth
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"cloture_month"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"cloture_month"})
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=2)
     * @Serializer\Groups({"cloture_month"})
     */
    private $month;

    /**
     * @ORM\Column(type="string", length=4)
     * @Serializer\Groups({"cloture_month"})
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="clotureMonths")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"cloture_month"})
     */
    private $operator;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bill", mappedBy="clotureMonth")
     * @Serializer\Groups({"cloture_month"})
     */
    private $bills;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Credit", mappedBy="clotureMonth")
     * @Serializer\Groups({"cloture_month"})
     */
    private $credits;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CashIn", mappedBy="clotureMonth")
     * @Serializer\Groups({"cloture_month"})
     */
    private $cashins;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Expence", mappedBy="clotureMonth")
     * @Serializer\Groups({"cloture_month"})
     */
    private $expences;

    public function __construct()
    {
        $this->bills = new ArrayCollection();
        $this->credits = new ArrayCollection();
        $this->cashins = new ArrayCollection();
        $this->expences = new ArrayCollection();
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

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

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
            $bill->setClotureMonth($this);
        }

        return $this;
    }

    public function removeBill(Bill $bill): self
    {
        if ($this->bills->contains($bill)) {
            $this->bills->removeElement($bill);
            // set the owning side to null (unless already changed)
            if ($bill->getClotureMonth() === $this) {
                $bill->setClotureMonth(null);
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
            $credit->setClotureMonth($this);
        }

        return $this;
    }

    public function removeCredit(Credit $credit): self
    {
        if ($this->credits->contains($credit)) {
            $this->credits->removeElement($credit);
            // set the owning side to null (unless already changed)
            if ($credit->getClotureMonth() === $this) {
                $credit->setClotureMonth(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CashIn[]
     */
    public function getCashins(): Collection
    {
        return $this->cashins;
    }

    public function addCashin(CashIn $cashin): self
    {
        if (!$this->cashins->contains($cashin)) {
            $this->cashins[] = $cashin;
            $cashin->setClotureMonth($this);
        }

        return $this;
    }

    public function removeCashin(CashIn $cashin): self
    {
        if ($this->cashins->contains($cashin)) {
            $this->cashins->removeElement($cashin);
            // set the owning side to null (unless already changed)
            if ($cashin->getClotureMonth() === $this) {
                $cashin->setClotureMonth(null);
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
            $expence->setClotureMonth($this);
        }

        return $this;
    }

    public function removeExpence(Expence $expence): self
    {
        if ($this->expences->contains($expence)) {
            $this->expences->removeElement($expence);
            // set the owning side to null (unless already changed)
            if ($expence->getClotureMonth() === $this) {
                $expence->setClotureMonth(null);
            }
        }

        return $this;
    }
}
