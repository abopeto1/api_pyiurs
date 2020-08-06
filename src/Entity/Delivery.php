<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"delivery:read"}},
 *      denormalizationContext={"groups"={"delivery:write"}},
 *      itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"delivery:read:item"}},
 *          },
 *      },
 * )
 * @ApiFilter(
 *      OrderFilter::class,
 *      properties={"created","id"},
 *      arguments={"orderParameterName"="_order"}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\DeliveryRepository")
 * @UniqueEntity("name")
 */
class Delivery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"delivery:read","delivery:read:item"})
     */
    private $id;

    /**
     * @var string the code of the delivery
     * 
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     * @Groups({"delivery:read","delivery:write","delivery:read:item"})
     */
    private $name;

    /**
     * @var string the agence of the delivery
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"delivery:write","delivery:read:item"})
     */
    private $agency;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"delivery:write","delivery:read:item"})
     */
    private $weight;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"delivery:read","delivery:read:item"})
     */
    private $created;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"delivery:write","delivery:read:item"})
     */
    private $description;

    /**
     * @ApiSubresource
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="delivery")
     * @Groups({"delivery:write"})
     */
    private $products;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"delivery:write","delivery:read:item"})
     */
    private $costs_agency;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"delivery:write","delivery:read:item"})
     */
    private $costs_other;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"delivery:write","delivery:read:item"})
     */
    private $details;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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

    public function getAgency(): ?string
    {
        return $this->agency;
    }

    public function setAgency(?string $agency): self
    {
        $this->agency = $agency;

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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setDelivery($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getDelivery() === $this) {
                $product->setDelivery(null);
            }
        }

        return $this;
    }

    public function getCostsAgency(): ?float
    {
        return $this->costs_agency;
    }

    public function setCostsAgency(?float $costs_agency): self
    {
        $this->costs_agency = $costs_agency;

        return $this;
    }

    public function getCostsOther(): ?float
    {
        return $this->costs_other;
    }

    public function setCostsOther(?float $costs_other): self
    {
        $this->costs_other = $costs_other;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }

    /**
     * @Groups({"delivery:read","delivery:read:item"})
     */
    public function getTotalProducts(): ?int
    {
        $total = count($this->getProducts());
        return $total;
    }

    /**
     * @Groups({"delivery:read","delivery:read:item"})
     */
    public function getproductsNotLoaded(): ?int
    {
        $filtered = $this->getProducts()->filter(function($product){
            return $product->getMoveStatus() === 0;
        });
        return count($filtered);
    }

    /**
     * @Groups({"delivery:read","delivery:read:item"})
     */
    public function getproductsLoaded(): ?int
    {
        $filtered = $this->getProducts()->filter(function($product){
            return $product->getMoveStatus() === 1;
        });
        return count($filtered);
    }

    /**
     * @Groups({"delivery:read","delivery:read:item"})
     */
    public function getPat(): ?float
    {
        $pat = 0;
        foreach($this->products as $product){
            $pat += $product->getPu() + $product->getCaa();
        }
        return round($pat, 2);
    }

    /**
     * @Groups({"delivery:read","delivery:read:item"})
     */
    public function getSellValue(): ?float
    {
        $sellValue = 0;
        foreach ($this->products as $product) {
            $sellValue += $product->getPv();
        }
        return round($sellValue,2);
    }

    /**
     * @Groups({"delivery:read","delivery:read:item"})
     */
    public function getLoadedValue(): ?float
    {
        $sellValue = 0;
        $filtered = $this->getProducts()->filter(function ($product) {
            return $product->getMoveStatus() === 1;
        });

        foreach ($filtered as $product) {
            $sellValue += $product->getPu() + $product->getPu();
        }
        return round($sellValue, 2);
    }

    /**
     * @Groups({"delivery:read","delivery:read:item"})
     */
    public function getNotLoadedValue(): ?float
    {
        $sellValue = 0;
        $filtered = $this->getProducts()->filter(function ($product) {
            return $product->getMoveStatus() === 0;
        });

        foreach ($filtered as $product) {
            $sellValue += $product->getPu() + $product->getCaa();
        }
        return round($sellValue, 2);
    }

    /**
     * @Groups({"delivery:read","delivery:read:item"})
     */
    public function getSelledValue(): ?float
    {
        $sellValue = 0;
        $filtered = $this->getProducts()->filter(function ($product) {
            return $product->getMoveStatus() === 1 && !$product->getStock()->getAvailable();
        });

        foreach ($filtered as $product) {
            $sellValue += $product->getPu() + $product->getCaa();
        }
        return round($sellValue, 2);
    }

    /**
     * @Groups({"delivery:read","delivery:read:item"})
     */
    public function getNetValue(): ?float
    {
        $sellValue = 0;
        $filtered = $this->getProducts()->filter(function ($product) {
            return $product->getMoveStatus() === 1 && !$product->getStock()->getAvailable();
        });

        foreach ($filtered as $product) {
            foreach($product->getBillDetails() as $billDetail)
            {
                if(!$billDetail->getRs())
                {
                    $sellValue += $billDetail->getNet();
                }
            }
        }
        return round($sellValue, 2);
    }
}
