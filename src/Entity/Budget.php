<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BudgetRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="budget_month_year", columns={"month","year","expence_account_id"})})
 */
class Budget
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"budget","expenceCompteCategory"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2)
     * @Serializer\Groups({"budget","expenceCompteCategory"})
     */
    private $month;

    /**
     * @ORM\Column(type="string", length=4)
     * @Serializer\Groups({"budget","expenceCompteCategory"})
     */
    private $year;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"budget","expenceCompteCategory"})
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ExpenceCompte", inversedBy="budgets")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"budget"})
     */
    private $expence_account;

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

    public function getExpenceAccount(): ?ExpenceCompte
    {
        return $this->expence_account;
    }

    public function setExpenceAccount(?ExpenceCompte $expence_account): self
    {
        $this->expence_account = $expence_account;

        return $this;
    }
}
