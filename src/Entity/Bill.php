<?php

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
 *  normalizationContext={"groups"={"bill:read"}},
 *  denormalizationContext={"groups"={"bill:write"}}
 * )
 * @ApiFilter(
 *      SearchFilter::Class,
 *      properties={"created":"partial"}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\BillRepository")
 * @ORM\Table(name="sell_synth")
 * @ORM\HasLifecycleCallbacks
 */
class Bill
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list_bills","one_bill","bill_detail","clotures","customer","users","type_paiement",
     * "cloture_month","segments","agent"})
     * @Groups({"bill:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50,name="SynthId",unique=true)
     * @Serializer\Groups({"list_bills","one_bill","bill_detail","report_cloture","users","cloture_month","customer","agent"})
     * @Groups({"bill:read"})
     */
    private $numero;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"list_bills","one_bill","agent"})
     * @Groups({"bill:read","bill:write"})
     */
    private $total;

    /**
     * @ORM\Column(type="float",name="tva")
     * @Serializer\Groups({"list_bills","one_bill","agent"})
     * @Groups({"bill:read","bill:write"})
     */
    private $taxe;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"list_bills","one_bill","bill_detail","report_cloture","list_customer","clotures",
     * "customer","users","type_paiement","cloture_month","agent"})
     * @Groups({"bill:read","bill:write"})
     */
    private $net;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"list_bills","one_bill","clotures","customer","users","type_paiement",
     * "cloture_month","agent"})
     * @Groups({"bill:read","bill:write"})
     */
    private $accompte;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"list_bills","one_bill","clotures","customer","users","type_paiement",
     * "cloture_month","agent"})
     * @Groups({"bill:read","bill:write"})
     */
    private $reste;

    /**
     * @ORM\Column(type="datetime",name="s_date")
     * @Serializer\Groups({"list_bills","one_bill","sells_reports","report_cloture","customer","users","type_paiement",
     * "cloture_month","agent"})
     * @Groups({"bill:read","bill:write"})
     */
    private $created;

    /**
     * @ORM\Column(type="integer",name="telOrMney",nullable=true)
     */
    private $telOrMoney;

    /**
     * @ApiSubresource
     * @ORM\OneToMany(targetEntity="App\Entity\BillDetails", mappedBy="bill", cascade={"persist"})
     * @Serializer\Groups({"list_bills","one_bill","report_cloture","list_customer",
     * "customer","agent"})
     * @Groups({"bill:write"})
     */
    private $billDetails;

    /**
     * @ApiSubresource
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="bills")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"list_bills","one_bill","bill_detail","report_cloture","users","type_paiement"})
     * @Groups({"bill:read","bill:write"})
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bill", inversedBy="bills")
     * @Serializer\Groups({"list_bills","one_bill"})
     */
    private $bill_reference;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bill", mappedBy="bill_reference")
     */
    private $bills;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ClotureDay", inversedBy="bills")
     */
    private $clotureDay;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bills")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"list_bills","one_bill","type_paiement","segments"})
     * @Groups({"bill:read","bill:write"})
     */
    private $operator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ClotureMonth", inversedBy="bills")
     */
    private $clotureMonth;

    /**
     * ApiSubresource
     * @ORM\ManyToOne(targetEntity="App\Entity\TypePaiement", inversedBy="bills")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"list_bills","one_bill","sells_reports","report_cloture","customer","clotures","users","agent"})
     * @Groups({"bill:read","bill:write"})
     */
    private $typePaiement;

    public function __construct()
    {
        $this->billDetails = new ArrayCollection();
        $this->bills = new ArrayCollection();
    }

    public function onPrePersist()
    {
      $this->created = new \DateTime(null, new \DateTimeZone("Africa/Kinshasa"));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

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

    public function getTaxe(): ?float
    {
        return $this->taxe;
    }

    public function setTaxe(float $taxe): self
    {
        $this->taxe = $taxe;

        return $this;
    }

    public function getNet(): ?float
    {
        return $this->net;
    }

    public function setNet(float $net): self
    {
        $this->net = $net;

        return $this;
    }

    public function getAccompte(): ?float
    {
        return $this->accompte;
    }

    public function setAccompte(float $accompte): self
    {
        $this->accompte = $accompte;

        return $this;
    }

    public function getReste(): ?float
    {
        return $this->reste;
    }

    public function setReste(float $reste): self
    {
        $this->reste = $reste;

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

    public function getTelOrMoney(): ?int
    {
        return $this->telOrMoney;
    }

    public function setTelOrMoney(int $telOrMoney): self
    {
        $this->telOrMoney = $telOrMoney;

        return $this;
    }

    /**
     * @return Collection|BillDetails[]
     */
    public function getBillDetails(): Collection
    {
        return $this->billDetails;
    }

    public function addBillDetail(BillDetails $billDetail): self
    {
        if (!$this->billDetails->contains($billDetail)) {
            $this->billDetails[] = $billDetail;
            $billDetail->setBill($this);
        }

        return $this;
    }

    public function removeBillDetail(BillDetails $billDetail): self
    {
        if ($this->billDetails->contains($billDetail)) {
            $this->billDetails->removeElement($billDetail);
            // set the owning side to null (unless already changed)
            if ($billDetail->getBill() === $this) {
                $billDetail->setBill(null);
            }
        }

        return $this;
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

    public function getBillReference(): ?self
    {
        return $this->bill_reference;
    }

    public function setBillReference(?self $bill_reference): self
    {
        $this->bill_reference = $bill_reference;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getBills(): Collection
    {
        return $this->bills;
    }

    public function addBill(self $bill): self
    {
        if (!$this->bills->contains($bill)) {
            $this->bills[] = $bill;
            $bill->setBillReference($this);
        }

        return $this;
    }

    public function removeBill(self $bill): self
    {
        if ($this->bills->contains($bill)) {
            $this->bills->removeElement($bill);
            // set the owning side to null (unless already changed)
            if ($bill->getBillReference() === $this) {
                $bill->setBillReference(null);
            }
        }

        return $this;
    }

    public function getClotureDay(): ?ClotureDay
    {
        return $this->clotureDay;
    }

    public function setClotureDay(?ClotureDay $clotureDay): self
    {
        $this->clotureDay = $clotureDay;

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

    public function getClotureMonth(): ?ClotureMonth
    {
        return $this->clotureMonth;
    }

    public function setClotureMonth(?ClotureMonth $clotureMonth): self
    {
        $this->clotureMonth = $clotureMonth;

        return $this;
    }

    public function getTypePaiement(): ?TypePaiement
    {
        return $this->typePaiement;
    }

    public function setTypePaiement(?TypePaiement $typePaiement): self
    {
        $this->typePaiement = $typePaiement;

        return $this;
    }
}
