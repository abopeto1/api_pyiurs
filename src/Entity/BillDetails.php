<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      subresourceOperations={
 *          "bill_billDetail_write"={
 *              "method"="POST",
 *              "denormalization_context"={"groups"={"bill:write"}}
 *          }
 *      },
 *      normalizationContext={"groups"={"billDetail:read"}}
 * )
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "bill.customer": "exact"
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\BillDetailsRepository")
 * @ORM\Table(name="sell_detail")
 * @ORM\HasLifecycleCallbacks
 */
class BillDetails
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer",name="SellID")
     * @Serializer\Groups({"bill_detail","list_bills","one_bill","report_cloture","list_products","stock",
     * "customer","stock_resume","segments","products"})
     * @Groups({"billDetail:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100,nullable=true)
     */
    private $pid;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"bill_detail","list_bills","one_bill","report_cloture"})
     * @Groups({"bill:write"})
     * @Groups({"billDetail:read"})
     */
    private $qte;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"list_bills","one_bill"})
     * @Groups({"bill:write"})
     */
    private $pu;

    /**
     * @ORM\Column(type="float",name="tva")
     * @Serializer\Groups({"list_bills","one_bill"})
     * @Groups({"bill:write"})
     */
    private $tax;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"bill_detail","list_bills","one_bill","sells_reports","report_cloture","customer",
     * "segments","products"})
     * @Groups({"billDetail:read","bill:write"})
     */
    private $net;

    /**
     * @ORM\Column(type="datetime",name="date")
     * @Serializer\Groups({"bill_detail","list_products","stock","stock_resume","segments"})
     * @Groups({"billDetail:read"})
     */
    private $created;

    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({"bill_detail","list_bills","sells_reports","list_customer","customer",
     * "stock_resume","segments","products"})
     * @Groups({"bill:write"})
     */
    private $rs;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bill", inversedBy="billDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"bill_detail","segments"})
     */
    private $bill;

    /**
     * @ApiSubresource
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="billDetails")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"list_bills","one_bill","bill_detail","report_cloture","customer"})
     * @Groups({"billDetail:read","bill:write"})
     */
    private $product;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"list_bills","one_bill","customer"})
     */
    private $point;

    public function onPrePersist()
    {
        $this->created = new \DateTime(null, new \DateTimeZone("Africa/Kinshasa"));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPid(): ?string
    {
        return $this->pid;
    }

    public function setPid(string $pid): self
    {
        $this->pid = $pid;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getPu(): ?float
    {
        return $this->pu;
    }

    public function setPu(float $pu): self
    {
        $this->pu = $pu;

        return $this;
    }

    public function getTax(): ?float
    {
        return $this->tax;
    }

    public function setTax(float $tax): self
    {
        $this->tax = $tax;

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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getRs(): ?bool
    {
        return $this->rs;
    }

    public function setRs(bool $rs): self
    {
        $this->rs = $rs;

        return $this;
    }

    public function getBill(): ?Bill
    {
        return $this->bill;
    }

    public function setBill(?Bill $bill): self
    {
        $this->bill = $bill;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getPoint(): ?float
    {
        return $this->point;
    }

    public function setPoint(float $point): self
    {
        $this->point = $point;

        return $this;
    }
}
