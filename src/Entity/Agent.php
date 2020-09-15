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
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"agent:read"}},
 *      subresourceOperations={
 *          "appointment_agent"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"appointment:read"}}
 *          },
 *          "user_agent_account"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"user:read"}}
 *          }
 *      }
 * )
 * @ApiFilter(
 *      SearchFilter::Class,
 *      properties={
 *          "user.id": "exact"
 *      })
 * @ORM\Entity(repositoryClass="App\Repository\AgentRepository")
 */
class Agent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"agent"})
     * @Groups({"agent:read","appointment:read","commission:read","user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"agent"})
     * @Groups({"agent:read","appointment:read","commission:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"agent"})
     * @Groups({"agent:read","appointment:read"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"agent"})
     * @Groups({"agent:read","appointment:read"})
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commission", mappedBy="seller")
     */
    private $commissions;

    /**
     * @ApiSubresource
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="agent_account", cascade={"persist", "remove"})
     * @Groups({"agent:read"})
     */
    private $user;

    public function __construct()
    {
        $this->credits = new ArrayCollection();
        $this->agentLoans = new ArrayCollection();
        $this->appointments = new ArrayCollection();
        $this->commissions = new ArrayCollection();
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

    /**
     * @return Collection|Commission[]
     */
    public function getCommissions(): Collection
    {
        return $this->commissions;
    }

    public function addCommission(Commission $commission): self
    {
        if (!$this->commissions->contains($commission)) {
            $this->commissions[] = $commission;
            $commission->setSeller($this);
        }

        return $this;
    }

    public function removeCommission(Commission $commission): self
    {
        if ($this->commissions->contains($commission)) {
            $this->commissions->removeElement($commission);
            // set the owning side to null (unless already changed)
            if ($commission->getSeller() === $this) {
                $commission->setSeller(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newAgent_account = null === $user ? null : $this;
        if ($user->getAgentAccount() !== $newAgent_account) {
            $user->setAgentAccount($newAgent_account);
        }

        return $this;
    }

    /**
     * @Groups({"agent:read"}) 
     */
    public function getMonthCommission(){
        $date = new \DateTime();

        if(!$this->getUser()){
            return 0;
        }

        $bills = $this->getUser()->getBills()->filter(function($e) use ($date){
            return $e->getCreated()->format('Ym') === $date->format('Ym');
        });
        $totalSell = 0;
        
        $thisCommission = $this->getCommissions()->filter(function($e) use ($date){
            return $e->getMonth() == $date->format('Y-m');
        })[0];

        foreach($bills as $bill){
            if($bill->getTypePaiement()->getId() === 2){
                $totalSell += $bill->getAccompte();
            } else {
                $totalSell += $bill->getNet();
            }
        }
        
        if(!$thisCommission){
            return 0;    
        }

        if(($totalSell / $thisCommission->getAmount()) < 0.5){
            return 0;
        }

        if(
            ($totalSell / $thisCommission->getAmount()) >= 0.5 
            && 
            ($totalSell / $thisCommission->getAmount()) <= 0.8){
            return 30;
        }
        
        if(($totalSell / $thisCommission->getAmount()) >= 0.8){
            return round($totalSell/10, 0);
        }
    }
}
