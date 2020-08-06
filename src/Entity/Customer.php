<?php

/*
 * This file is part of the API for Pyiurs Boutique POS.
 *
 * (c) Arnold Bopeto <abopeto1@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  subresourceOperations={
 *      "appointment_customer"={
 *          "method"="GET",
 *          "normalization_context"={"groups"={"appointment:read"}}
 *      }
 *  }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 * @ORM\Table(name="client")
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"customer","list_customer","list_bills","bill_detail","agent",
     * "one_bill","users","type_paiement"})
     * @Groups({"appointment:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15,name="tel",unique=true)
     * @Serializer\Groups({"customer","bill_detail","list_customer","list_bills","users","type_paiement","agent"})
     * @Groups({"appointment:read"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=100,name="nom")
     * @Serializer\Groups({"customer","list_bills","one_bill","bill_detail","report_cloture"
     * ,"agent","list_customer","users","type_paiement"})
     * @Groups({"appointment:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100,nullable=true)
     * @Serializer\Groups({"customer"})
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime", nullable=true,name="addDate")
     * @Serializer\Groups({"customer","list_customer"})
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bill", mappedBy="customer")
     * @Serializer\Groups({"customer","agent"})
     */
    private $bills;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CustomerCategorie", inversedBy="customers")
     * @Serializer\Groups({"customer","list_bills","list_customer"})
     */
    private $categorie;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"customer","list_customer","one_bill"})
     */
    private $points;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CustomerStatusLog", mappedBy="customer", orphanRemoval=true)
     */
    private $customerStatusLogs;

    /**
     * @Serializer\Groups({"list_customer"})
     */
    private $total_article_sell;

    /**
     * @Serializer\Groups({"list_customer"})
     */
    private $total_article_count;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Appointment", mappedBy="customer", orphanRemoval=true)
     */
    private $appointments;

    public function __construct()
    {
        $this->bills = new ArrayCollection();
        $this->customerStatusLogs = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
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
            $bill->setCustomer($this);
        }

        return $this;
    }

    public function removeBill(Bill $bill): self
    {
        if ($this->bills->contains($bill)) {
            $this->bills->removeElement($bill);
            // set the owning side to null (unless already changed)
            if ($bill->getCustomer() === $this) {
                $bill->setCustomer(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?CustomerCategorie
    {
        return $this->categorie;
    }

    public function setCategorie(?CustomerCategorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getPoints(): ?float
    {
        return $this->points;
    }

    public function setPoints(?float $points): self
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @return Collection|CustomerStatusLog[]
     */
    public function getCustomerStatusLogs(): Collection
    {
        return $this->customerStatusLogs;
    }

    public function addCustomerStatusLog(CustomerStatusLog $customerStatusLog): self
    {
        if (!$this->customerStatusLogs->contains($customerStatusLog)) {
            $this->customerStatusLogs[] = $customerStatusLog;
            $customerStatusLog->setCustomer($this);
        }

        return $this;
    }

    public function removeCustomerStatusLog(CustomerStatusLog $customerStatusLog): self
    {
        if ($this->customerStatusLogs->contains($customerStatusLog)) {
            $this->customerStatusLogs->removeElement($customerStatusLog);
            // set the owning side to null (unless already changed)
            if ($customerStatusLog->getCustomer() === $this) {
                $customerStatusLog->setCustomer(null);
            }
        }

        return $this;
    }

    public function getTotalArticleSell(): ?int
    {
        return $this->total_article_sell;
    }

    public function setTotalArticleSell(int $total_article_sell): self
    {
        $this->total_article_sell = $total_article_sell;

        return $this;
    }

    public function getTotalArticleCount(): ?int
    {
        return $this->total_article_count;
    }

    public function setTotalArticleCount(int $total_article_count): self
    {
        $this->total_article_count = $total_article_count;

        return $this;
    }

    /**
     * @return Collection|Appointment[]
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): self
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments[] = $appointment;
            $appointment->setCustomer($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->contains($appointment)) {
            $this->appointments->removeElement($appointment);
            // set the owning side to null (unless already changed)
            if ($appointment->getCustomer() === $this) {
                $appointment->setCustomer(null);
            }
        }

        return $this;
    }
}
