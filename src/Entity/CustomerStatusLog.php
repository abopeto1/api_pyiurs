<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\CustomerStatusLogRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CustomerStatusLog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="customerStatusLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CustomerCategorie", inversedBy="customerStatusLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $status;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    public function __construct()
    {
      $this->created = new \DateTime(null, new \DateTimeZone("Africa/Kinshasa"));
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCategory(): ?CustomerCategorie
    {
        return $this->category;
    }

    public function setCategory(?CustomerCategorie $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }
}
