<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StockLogRepository")
 */
class StockLog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date",unique=true)
     */
    private $created;

    /**
     * @ORM\Column(type="integer")
     */
    private $open;

    /**
     * @ORM\Column(type="integer")
     */
    private $added;

    /**
     * @ORM\Column(type="integer")
     */
    private $returned;

    /**
     * @ORM\Column(type="integer")
     */
    private $selled;

    /**
     * @ORM\Column(type="integer")
     */
    private $closed;

    /**
     * @ORM\Column(type="float")
     */
    private $valueClosed;

    /**
     * @ORM\Column(type="float")
     */
    private $patValueClosed;

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

    public function getOpen(): ?int
    {
        return $this->open;
    }

    public function setOpen(int $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getAdded(): ?int
    {
        return $this->added;
    }

    public function setAdded(int $added): self
    {
        $this->added = $added;

        return $this;
    }

    public function getReturned(): ?int
    {
        return $this->returned;
    }

    public function setReturned(int $returned): self
    {
        $this->returned = $returned;

        return $this;
    }

    public function getSelled(): ?int
    {
        return $this->selled;
    }

    public function setSelled(int $selled): self
    {
        $this->selled = $selled;

        return $this;
    }

    public function getClosed(): ?int
    {
        return $this->closed;
    }

    public function setClosed(int $closed): self
    {
        $this->closed = $closed;

        return $this;
    }

    public function getValueClosed(): ?float
    {
        return $this->valueClosed;
    }

    public function setValueClosed(float $valueClosed): self
    {
        $this->valueClosed = $valueClosed;

        return $this;
    }

    public function getPatValueClosed(): ?float
    {
        return $this->patValueClosed;
    }

    public function setPatValueClosed(float $patValueClosed): self
    {
        $this->patValueClosed = $patValueClosed;

        return $this;
    }
}
