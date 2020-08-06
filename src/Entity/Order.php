<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="product_order")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"order","order_echeance"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"order","order_echeance"})
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"order","order_echeance"})
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Groups({"order"})
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"order","order_echeance"})
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=5)
     * @Serializer\Groups({"order","order_echeance"})
     */
    private $currency;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Serializer\Groups({"order"})
     */
    private $delivery_date;

    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({"order"})
     */
    private $deliveried;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Groups({"order"})
     */
    private $weight;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Groups({"order"})
     */
    private $transfert_costs;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Groups({"order"})
     */
    private $fret_costs;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Groups({"order","order_echeance"})
     */
    private $taux;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderBill", mappedBy="orders")
     * @Serializer\Groups({"order"})
     */
    private $orderBills;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\Groups({"order"})
     */
    private $nbr_articles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderEcheance", mappedBy="the_order")
     * @Serializer\Groups({"order"})
     */
    private $orderEcheances;

    public function __construct()
    {
        $this->orderBills = new ArrayCollection();
        $this->orderEcheances = new ArrayCollection();
    }

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->Description = $description;

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

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->delivery_date;
    }

    public function setDeliveryDate(?\DateTimeInterface $delivery_date): self
    {
        $this->delivery_date = $delivery_date;

        return $this;
    }

    public function getDeliveried(): ?bool
    {
        return $this->deliveried;
    }

    public function setDeliveried(bool $deliveried): self
    {
        $this->deliveried = $deliveried;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getTransfertCosts(): ?float
    {
        return $this->transfert_costs;
    }

    public function setTransfertCosts(?float $transfert_costs): self
    {
        $this->transfert_costs = $transfert_costs;

        return $this;
    }

    public function getFretCosts(): ?float
    {
        return $this->fret_costs;
    }

    public function setFretCosts(?float $fret_costs): self
    {
        $this->fret_costs = $fret_costs;

        return $this;
    }

    public function getTaux(): ?float
    {
        return $this->taux;
    }

    public function setTaux(?float $taux): self
    {
        $this->taux = $taux;

        return $this;
    }

    /**
     * @return Collection|OrderBill[]
     */
    public function getOrderBills(): Collection
    {
        return $this->orderBills;
    }

    public function addOrderBill(OrderBill $orderBill): self
    {
        if (!$this->orderBills->contains($orderBill)) {
            $this->orderBills[] = $orderBill;
            $orderBill->setOrders($this);
        }

        return $this;
    }

    public function removeOrderBill(OrderBill $orderBill): self
    {
        if ($this->orderBills->contains($orderBill)) {
            $this->orderBills->removeElement($orderBill);
            // set the owning side to null (unless already changed)
            if ($orderBill->getOrders() === $this) {
                $orderBill->setOrders(null);
            }
        }

        return $this;
    }

    public function getNbrArticles(): ?int
    {
        return $this->nbr_articles;
    }

    public function setNbrArticles(?int $nbr_articles): self
    {
        $this->nbr_articles = $nbr_articles;

        return $this;
    }

    /**
     * @return Collection|OrderEcheance[]
     */
    public function getOrderEcheances(): Collection
    {
        return $this->orderEcheances;
    }

    public function addOrderEcheance(OrderEcheance $orderEcheance): self
    {
        if (!$this->orderEcheances->contains($orderEcheance)) {
            $this->orderEcheances[] = $orderEcheance;
            $orderEcheance->setTheOrder($this);
        }

        return $this;
    }

    public function removeOrderEcheance(OrderEcheance $orderEcheance): self
    {
        if ($this->orderEcheances->contains($orderEcheance)) {
            $this->orderEcheances->removeElement($orderEcheance);
            // set the owning side to null (unless already changed)
            if ($orderEcheance->getTheOrder() === $this) {
                $orderEcheance->setTheOrder(null);
            }
        }

        return $this;
    }
}
