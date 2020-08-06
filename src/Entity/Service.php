<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServiceRepository")
 */
class Service
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"service"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"service"})
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"service"})
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"service"})
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=5)
     * @Serializer\Groups({"service"})
     */
    private $currency;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="services")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"service"})
     */
    private $operator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ClotureDay", inversedBy="services")
     */
    private $cloture_day;

    /**
     * @ORM\Column(type="integer")
     */
    private $taux;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getOperator(): ?User
    {
        return $this->operator;
    }

    public function setOperator(?User $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    public function getClotureDay(): ?ClotureDay
    {
        return $this->cloture_day;
    }

    public function setClotureDay(?ClotureDay $cloture_day): self
    {
        $this->cloture_day = $cloture_day;

        return $this;
    }

    public function getTaux(): ?int
    {
        return $this->taux;
    }

    public function setTaux(int $taux): self
    {
        $this->taux = $taux;

        return $this;
    }
}
