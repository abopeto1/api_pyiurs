<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\TypePaiementRepository")
 */
class TypePaiement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list_bills","one_bill","bill_detail","clotures","users","type_paiement","customer","agent"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"list_bills","one_bill","bill_detail","report_cloture","users","type_paiement","customer","agent"})
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bill", mappedBy="typePaiement")
     * @Serializer\Groups({"type_paiement"})
     */
    private $bills;

    public function __construct()
    {
        $this->bills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $bill->setTypePaiement($this);
        }

        return $this;
    }

    public function removeBill(Bill $bill): self
    {
        if ($this->bills->contains($bill)) {
            $this->bills->removeElement($bill);
            // set the owning side to null (unless already changed)
            if ($bill->getTypePaiement() === $this) {
                $bill->setTypePaiement(null);
            }
        }

        return $this;
    }
}
