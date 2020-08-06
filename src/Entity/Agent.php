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
 *      subresourceOperations={
 *          "appointment_agent"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"appointment:read"}}
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\AgentRepository")
 */
class Agent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"agent"})
     * @Groups({"appointment:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"agent"})
     * @Groups({"appointment:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"agent"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"agent"})
     */
    private $fonction;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"agent"})
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=9)
     * @Serializer\Groups({"agent"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=3)
     * @Serializer\Groups({"agent"})
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Groups({"agent"})
     */
    private $adress;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"agent"})
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     * @Serializer\Groups({"agent"})
     */
    private $private_telephone;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Customer", cascade={"persist", "remove"})
     * @Serializer\Groups({"agent"})
     */
    private $customer_account;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Credit", mappedBy="agent")
     * @Serializer\Groups({"agent"})
     */
    private $credits;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AgentLoan", mappedBy="agent")
     * @Serializer\Groups({"agent"})
     */
    private $agentLoans;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Appointment", mappedBy="agent", orphanRemoval=true)
     */
    private $appointments;

    public function __construct()
    {
        $this->credits = new ArrayCollection();
        $this->agentLoans = new ArrayCollection();
        $this->appointments = new ArrayCollection();
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

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

    public function getPrivateTelephone(): ?string
    {
        return $this->private_telephone;
    }

    public function setPrivateTelephone(?string $private_telephone): self
    {
        $this->private_telephone = $private_telephone;

        return $this;
    }

    public function getCustomerAccount(): ?Customer
    {
        return $this->customer_account;
    }

    public function setCustomerAccount(?Customer $customer_account): self
    {
        $this->customer_account = $customer_account;

        return $this;
    }

    /**
     * @return Collection|Credit[]
     */
    public function getCredits(): Collection
    {
        return $this->credits;
    }

    public function addCredit(Credit $credit): self
    {
        if (!$this->credits->contains($credit)) {
            $this->credits[] = $credit;
            $credit->setAgent($this);
        }

        return $this;
    }

    public function removeCredit(Credit $credit): self
    {
        if ($this->credits->contains($credit)) {
            $this->credits->removeElement($credit);
            // set the owning side to null (unless already changed)
            if ($credit->getAgent() === $this) {
                $credit->setAgent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AgentLoan[]
     */
    public function getAgentLoans(): Collection
    {
        return $this->agentLoans;
    }

    public function addAgentLoan(AgentLoan $agentLoan): self
    {
        if (!$this->agentLoans->contains($agentLoan)) {
            $this->agentLoans[] = $agentLoan;
            $agentLoan->setAgent($this);
        }

        return $this;
    }

    public function removeAgentLoan(AgentLoan $agentLoan): self
    {
        if ($this->agentLoans->contains($agentLoan)) {
            $this->agentLoans->removeElement($agentLoan);
            // set the owning side to null (unless already changed)
            if ($agentLoan->getAgent() === $this) {
                $agentLoan->setAgent(null);
            }
        }

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
            $appointment->setAgent($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->contains($appointment)) {
            $this->appointments->removeElement($appointment);
            // set the owning side to null (unless already changed)
            if ($appointment->getAgent() === $this) {
                $appointment->setAgent(null);
            }
        }

        return $this;
    }
}
