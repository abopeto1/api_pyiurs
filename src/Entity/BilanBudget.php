<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BilanBudgetRepository")
 * @ORM\Table(
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="bilan_budget_month_year", columns={"month","year","bilan_account_id"})
 * })
 */
class BilanBudget
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"bilan_budget"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2)
     * @Serializer\Groups({"bilan_budget"})
     */
    private $month;

    /**
     * @ORM\Column(type="string", length=4)
     * @Serializer\Groups({"bilan_budget"})
     */
    private $year;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"bilan_budget"})
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BilanAccount", inversedBy="bilanBudgets")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"bilan_budget"})
     */
    private $bilan_account;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getBilanAccount(): ?BilanAccount
    {
        return $this->bilan_account;
    }

    public function setBilanAccount(?BilanAccount $bilan_account): self
    {
        $this->bilan_account = $bilan_account;

        return $this;
    }
}
