<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExpenceCompteRepository")
 * @ORM\Table(name="compte")
 */
class ExpenceCompte
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"expence","expence_compte","expenceCompteCategory","budget"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     * @Serializer\Groups({"expence","expence_compte","expenceCompteCategory","createExpenceCompte"})
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255,name="libele")
     * @Serializer\Groups({"expence","expence_compte","expenceCompteCategory","budget","createExpenceCompte"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ExpenceCompteCategorie", inversedBy="expenceAccounts")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"expence_compte","createExpenceCompte"})
     */
    private $expenceCompteCategorie;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Expence", mappedBy="expenceCompte")
     * @Serializer\Groups({"expenceCompteCategory"})
     */
    private $expences;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Budget", mappedBy="expence_account")
     * @Serializer\Groups({"expenceCompteCategory"})
     */
    private $budgets;

    public function __construct()
    {
        $this->expences = new ArrayCollection();
        $this->budgets = new ArrayCollection();
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

    public function getExpenceCompteCategorie(): ?ExpenceCompteCategorie
    {
        return $this->expenceCompteCategorie;
    }

    public function setExpenceCompteCategorie(?ExpenceCompteCategorie $expenceCompteCategorie): self
    {
        $this->expenceCompteCategorie = $expenceCompteCategorie;

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
            $expence->setExpenceAccount($this);
        }

        return $this;
    }

    public function removeExpence(Expence $expence): self
    {
        if ($this->expences->contains($expence)) {
            $this->expences->removeElement($expence);
            // set the owning side to null (unless already changed)
            if ($expence->getExpenceAccount() === $this) {
                $expence->setExpenceAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Budget[]
     */
    public function getBudgets(): Collection
    {
        return $this->budgets;
    }

    public function addBudget(Budget $budget): self
    {
        if (!$this->budgets->contains($budget)) {
            $this->budgets[] = $budget;
            $budget->setExpenceAccount($this);
        }

        return $this;
    }

    public function removeBudget(Budget $budget): self
    {
        if ($this->budgets->contains($budget)) {
            $this->budgets->removeElement($budget);
            // set the owning side to null (unless already changed)
            if ($budget->getExpenceAccount() === $this) {
                $budget->setExpenceAccount(null);
            }
        }

        return $this;
    }
}
