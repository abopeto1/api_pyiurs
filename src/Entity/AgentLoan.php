<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AgentLoanRepository")
 * @ORM\Table(
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="agent_loan_period", columns={"period","agent_id"})
 * })
 */
class AgentLoan
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"agent"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"agent"})
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=10)
     * @Serializer\Groups({"agent"})
     */
    private $period;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"agent"})
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=5)
     * @Serializer\Groups({"agent"})
     */
    private $currency;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Groups({"agent"})
     */
    private $taux;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agent", inversedBy="agentLoans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agent;

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

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): self
    {
        $this->period = $period;

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

    public function getTaux(): ?float
    {
        return $this->taux;
    }

    public function setTaux(float $taux): self
    {
        $this->taux = $taux;

        return $this;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }
}
