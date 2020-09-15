<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"commission:read"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CommissionRepository")
 * @ORM\Table(
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="commission_agent_month", columns={"seller_id","month"})
 * })
 */
class Commission
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"commission:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=7)
     * @Groups({"commission:read"})
     */
    private $month;

    /**
     * @ApiSubresource
     * @ORM\ManyToOne(targetEntity="App\Entity\Agent", inversedBy="commissions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"commission:read"})
     */
    private $seller;

    /**
     * @ORM\Column(type="float")
     * @Groups({"commission:read"})
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function getSeller(): ?Agent
    {
        return $this->seller;
    }

    public function setSeller(?Agent $seller): self
    {
        $this->seller = $seller;

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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }
}
