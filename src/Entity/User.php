<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list_bills","users","type_paiement",
     * "clotures","segments","cloture_month","service","auth_tokens","user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     */
    private $login;

    /**
     * @ORM\Column(type="json")
     * @Serializer\Groups({"auth_tokens"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank
     */
    protected $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Serializer\Groups({"list_bills","users","type_paiement","clotures","segments","cloture_month",
     * "service","auth_tokens","user"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     * @Serializer\Groups({"list_bills","users","type_paiement","clotures","cloture_month",
     * "service","auth_tokens","user"})
     */
    private $lastname;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bill", mappedBy="operator")
     * @Serializer\Groups({"users"})
     */
    private $bills;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ClotureMonth", mappedBy="operator")
     */
    private $clotureMonths;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Expence", mappedBy="operator")
     */
    private $expences;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ClotureDay", mappedBy="operator")
     */
    private $clotureDays;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Service", mappedBy="operator")
     */
    private $services;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AuthToken", mappedBy="user", orphanRemoval=true)
     */
    private $authTokens;

    /**
     * @Serializer\Groups({"users","user"})
     */
    private $total_sell_month;

    public function __construct()
    {
        $this->bills = new ArrayCollection();
        $this->clotureMonths = new ArrayCollection();
        $this->expences = new ArrayCollection();
        $this->clotureDays = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->authTokens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

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
            $bill->setOperator($this);
        }

        return $this;
    }

    public function removeBill(Bill $bill): self
    {
        if ($this->bills->contains($bill)) {
            $this->bills->removeElement($bill);
            // set the owning side to null (unless already changed)
            if ($bill->getOperator() === $this) {
                $bill->setOperator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ClotureMonth[]
     */
    public function getClotureMonths(): Collection
    {
        return $this->clotureMonths;
    }

    public function addClotureMonth(ClotureMonth $clotureMonth): self
    {
        if (!$this->clotureMonths->contains($clotureMonth)) {
            $this->clotureMonths[] = $clotureMonth;
            $clotureMonth->setOperator($this);
        }

        return $this;
    }

    public function removeClotureMonth(ClotureMonth $clotureMonth): self
    {
        if ($this->clotureMonths->contains($clotureMonth)) {
            $this->clotureMonths->removeElement($clotureMonth);
            // set the owning side to null (unless already changed)
            if ($clotureMonth->getOperator() === $this) {
                $clotureMonth->setOperator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Expence[]
     */
    public function getExpences(): Collection
    {
        return $this->expences;
    }

    public function addExpence(Expence $expence): self
    {
        if (!$this->expences->contains($expence)) {
            $this->expences[] = $expence;
            $expence->setOperator($this);
        }

        return $this;
    }

    public function removeExpence(Expence $expence): self
    {
        if ($this->expences->contains($expence)) {
            $this->expences->removeElement($expence);
            // set the owning side to null (unless already changed)
            if ($expence->getOperator() === $this) {
                $expence->setOperator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ClotureDay[]
     */
    public function getClotureDays(): Collection
    {
        return $this->clotureDays;
    }

    public function addClotureDay(ClotureDay $clotureDay): self
    {
        if (!$this->clotureDays->contains($clotureDay)) {
            $this->clotureDays[] = $clotureDay;
            $clotureDay->setOperator($this);
        }

        return $this;
    }

    public function removeClotureDay(ClotureDay $clotureDay): self
    {
        if ($this->clotureDays->contains($clotureDay)) {
            $this->clotureDays->removeElement($clotureDay);
            // set the owning side to null (unless already changed)
            if ($clotureDay->getOperator() === $this) {
                $clotureDay->setOperator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setOperator($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->contains($service)) {
            $this->services->removeElement($service);
            // set the owning side to null (unless already changed)
            if ($service->getOperator() === $this) {
                $service->setOperator(null);
            }
        }

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return Collection|AuthToken[]
     */
    public function getAuthTokens(): Collection
    {
        return $this->authTokens;
    }

    public function addAuthToken(AuthToken $authToken): self
    {
        if (!$this->authTokens->contains($authToken)) {
            $this->authTokens[] = $authToken;
            $authToken->setUser($this);
        }

        return $this;
    }

    public function removeAuthToken(AuthToken $authToken): self
    {
        if ($this->authTokens->contains($authToken)) {
            $this->authTokens->removeElement($authToken);
            // set the owning side to null (unless already changed)
            if ($authToken->getUser() === $this) {
                $authToken->setUser(null);
            }
        }

        return $this;
    }

    public function getTotalSellMonth(): ?int
    {
        return $this->total_sell_month;
    }

    public function setTotalSellMonth(int $total_sell_month): self
    {
        $this->total_sell_month = $total_sell_month;

        return $this;
    }
}
