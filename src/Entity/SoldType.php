<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\SoldTypeRepository")
 */
class SoldType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"solde_type","solde","one_sold","productByCodebarre"})
     * @Groups({"product:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"solde_type","solde","one_sold"})
     * @Groups({"product:read"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SoldOut", mappedBy="type")
     * @Serializer\Groups({"solde_type","one_sold"})
     */
    private $promotions;

    /**
     * @ORM\Column(type="string", length=10)
     * @Serializer\Groups({"solde_type","solde","one_sold"})
     */
    private $label;

    public function __construct()
    {
        $this->promotions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|SoldOut[]
     */
    public function getSoldOuts(): Collection
    {
        return $this->soldOuts;
    }

    public function addSoldOut(SoldOut $promotion): self
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions[] = $promotion;
            $promotion->setType($this);
        }

        return $this;
    }

    public function removeSoldOut(SoldOut $promotion): self
    {
        if ($this->promotions->contains($promotion)) {
            $this->promotions->removeElement($promotion);
            // set the owning side to null (unless already changed)
            if ($promotion->getType() === $this) {
                $promotion->setType(null);
            }
        }

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
