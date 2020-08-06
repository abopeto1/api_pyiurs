<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExpenceCompteCategorieRepository")
 */
class ExpenceCompteCategorie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"expenceCompteCategory","expence_compte","createExpenceCompte"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     * @Serializer\Groups({"expenceCompteCategory"})
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"expenceCompteCategory","expence_compte"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExpenceCompte", mappedBy="expenceCompteCategorie")
     * @Serializer\Groups({"expenceCompteCategory"})
     */
    private $expenceAccounts;

    public function __construct()
    {
        $this->expenceAccounts = new ArrayCollection();
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
     * @return Collection|ExpenceCompte[]
     */
    public function getExpenceAccounts(): Collection
    {
        return $this->expenceAccounts;
    }

    public function addExpenceAccount(ExpenceCompte $expenceAccount): self
    {
        if (!$this->expenceAccounts->contains($expenceAccount)) {
            $this->expenceAccounts[] = $expenceAccount;
            $expenceAccount->setExpenceCompteCategorie($this);
        }

        return $this;
    }

    public function removeExpenceAccount(ExpenceCompte $expenceAccount): self
    {
        if ($this->expenceAccounts->contains($expenceAccount)) {
            $this->expenceAccounts->removeElement($expenceAccount);
            // set the owning side to null (unless already changed)
            if ($expenceAccount->getExpenceCompteCategorie() === $this) {
                $expenceAccount->setExpenceCompteCategorie(null);
            }
        }

        return $this;
    }
}
