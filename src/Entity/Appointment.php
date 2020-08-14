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
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  normalizationContext={ "groups"={"appointment:read"}},
 *  denormalizationContext={ "groups"={"appointment:write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\AppointmentRepository")
 */
class Appointment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("appointment:read")
     */
    private $id;

    /**
     * @ApiSubresource
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="appointments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"appointment:read","appointment:write"})
     */
    private $customer;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("appointment:read")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"appointment:read","appointment:write"})
     */
    private $planned;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"appointment:read"})
     */
    private $created;

    /**
     * @ApiSubresource
     * @ORM\ManyToOne(targetEntity="App\Entity\Agent", inversedBy="appointments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"appointment:read","appointment:write"})
     */
    private $agent;

    /**
     * @ApiSubresource
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="appointments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"appointment:read","appointment:write"})
     */
    private $service;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"appointment:read"})
     */
    private $code;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPlanned(): ?\DateTimeInterface
    {
        return $this->planned;
    }

    public function setPlanned(\DateTimeInterface $planned): self
    {
        $this->planned = $planned;

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

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function getService(): ?Product
    {
        return $this->service;
    }

    public function setService(?Product $service): self
    {
        $this->service = $service;

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
}
