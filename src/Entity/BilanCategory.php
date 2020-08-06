<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BilanCategoryRepository")
 */
class BilanCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BilanAccount", mappedBy="category")
     */
    private $bilanAccounts;

    public function __construct()
    {
        $this->bilanAccounts = new ArrayCollection();
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

    /**
     * @return Collection|BilanAccount[]
     */
    public function getBilanAccounts(): Collection
    {
        return $this->bilanAccounts;
    }

    public function addBilanAccount(BilanAccount $bilanAccount): self
    {
        if (!$this->bilanAccounts->contains($bilanAccount)) {
            $this->bilanAccounts[] = $bilanAccount;
            $bilanAccount->setCategory($this);
        }

        return $this;
    }

    public function removeBilanAccount(BilanAccount $bilanAccount): self
    {
        if ($this->bilanAccounts->contains($bilanAccount)) {
            $this->bilanAccounts->removeElement($bilanAccount);
            // set the owning side to null (unless already changed)
            if ($bilanAccount->getCategory() === $this) {
                $bilanAccount->setCategory(null);
            }
        }

        return $this;
    }
}
