<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BilanAccountRepository")
 */
class BilanAccount
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"bilan_budget"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"bilan_budget"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BilanCategory", inversedBy="bilanAccounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BilanBudget", mappedBy="bilan_account", orphanRemoval=true)
     */
    private $bilanBudgets;

    private $value_month;

    public function __construct()
    {
        $this->bilanBudgets = new ArrayCollection();
        $this->value_month = 0;
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

    public function getCategory(): ?BilanCategory
    {
        return $this->category;
    }

    public function setCategory(?BilanCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|BilanBudget[]
     */
    public function getBilanBudgets(): Collection
    {
        return $this->bilanBudgets;
    }

    public function addBilanBudget(BilanBudget $bilanBudget): self
    {
        if (!$this->bilanBudgets->contains($bilanBudget)) {
            $this->bilanBudgets[] = $bilanBudget;
            $bilanBudget->setBilanAccount($this);
        }

        return $this;
    }

    public function removeBilanBudget(BilanBudget $bilanBudget): self
    {
        if ($this->bilanBudgets->contains($bilanBudget)) {
            $this->bilanBudgets->removeElement($bilanBudget);
            // set the owning side to null (unless already changed)
            if ($bilanBudget->getBilanAccount() === $this) {
                $bilanBudget->setBilanAccount(null);
            }
        }

        return $this;
    }

    public function getValueMonth(): ?float
    {
        return $this->value_month;
    }

    public function setValueMonth(float $value_month): self
    {
        $this->value_month = $value_month;

        return $this;
    }
}
