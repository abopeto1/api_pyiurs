<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\CustomerCategorieRepository")
 */
class CustomerCategorie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"customer","list_bills","customer_categories","list_customer"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"customer","list_bills","customer_categories","list_customer"})
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $quota;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Customer", mappedBy="categorie")
     */
    private $customers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CustomerStatusLog", mappedBy="category", orphanRemoval=true)
     */
    private $customerStatusLogs;

    /**
     * @Serializer\Groups({"customer","list_bills","customer_categories"})
     */
    private $totalCustomer;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
        $this->customerStatusLogs = new ArrayCollection();
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

    public function getQuota(): ?float
    {
        return $this->quota;
    }

    public function setQuota(float $quota): self
    {
        $this->quota = $quota;

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setCategorie($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->contains($customer)) {
            $this->customers->removeElement($customer);
            // set the owning side to null (unless already changed)
            if ($customer->getCategorie() === $this) {
                $customer->setCategorie(null);
            }
        }

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
            $customerStatusLog->setCategory($this);
        }

        return $this;
    }

    public function removeCustomerStatusLog(CustomerStatusLog $customerStatusLog): self
    {
        if ($this->customerStatusLogs->contains($customerStatusLog)) {
            $this->customerStatusLogs->removeElement($customerStatusLog);
            // set the owning side to null (unless already changed)
            if ($customerStatusLog->getCategory() === $this) {
                $customerStatusLog->setCategory(null);
            }
        }

        return $this;
    }

    public function getTotalCustomer(): ?int
    {
        return $this->totalCustomer;
    }

    public function setTotalCustomer(int $totalCustomer): self
    {
        $this->totalCustomer = $totalCustomer;

        return $this;
    }
}
