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
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      subresourceOperations={
 *          "service_appointment"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"appointment:read"}}
 *          },
 *          "billDetail_product"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"billDetail:read"}}
 *          },
 *          "productSample_product"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"product_sample:read"}},
 *          },
 *          "delivery_product"={
 *              "method"="POST",
 *              "denormalization_context"={"groups"={"delivery_product:write"}},
 *          },
 *          "type_product"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"type:write"}},
 *          },
 *      },
 *      normalizationContext={"groups"={"product:read"}},
 *      denormalizationContext={"groups"={"product:write"}}
 * )
 * @ApiFilter(
 *      SearchFilter::Class,
 *      properties={
 *          "type.segment.department": "exact",
 *          "codebarre": "iexact",
 *          "delivery": "exact",
 *          "moveStatus":"exact" ,
 *      }
 * )
 * @ApiFilter(
 *      BooleanFilter::Class,
 *      properties={
 *          "stock.available"
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"products","warehouses","stock","productByCodebarre","one_bill","product","list_products"
     * ,"bill_detail","report_cloture","warehouses","one_sold","product_one_sold","solde","customer","list_bills",
     * "resume","stock_resume","one_warehouse","inventories","inventory","segments","type_stats"})
     * @Groups({"product:read","appointment:read","billDetail:read","product_sample:read","type:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Serializer\Groups({"products","stock","productByCodebarre","list_products","sells_reports",
     * "report_cloture","one_sold","product_one_sold","solde","one_warehouse","inventory"})
     * @Groups({"product:read","delivery:write"})
     */
    private $cat;

    /**
     * @ORM\Column(type="string", length=100, name="Codebare")
     * @Serializer\Groups({"products","productByCodebarre","one_bill","list_products","sells_reports",
     * "report_cloture","one_sold","product_one_sold","solde","one_warehouse","inventory","segments",
     * "list_bills"})
     * @Groups({"product:read","product:write","appointment:read","delivery:write"})
     */
    private $codebarre;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Serializer\Groups({"products","productByCodebarre","list_products","one_sold","product_one_sold","solde",
     * "one_warehouse"})
     * @Groups({"product:read","product:write","delivery:write"})
     */
    private $taille;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"products","warehouses","stock","productByCodebarre","one_bill","list_products",
     * "bill_detail","warehouses","one_sold","product_one_sold","solde","list_bills","resume","stock_resume",
     * "one_warehouse","type_stats"})
     * @Groups({"product:read","product:write","delivery:write"})
     */
    private $pu;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Serializer\Groups({"products","productByCodebarre","list_products","one_sold","product_one_sold","solde",
     * "one_warehouse"})
     * @Groups({"product:read","product:write","delivery:write"})
     */
    private $couleur;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Serializer\Groups({"products","productByCodebarre","list_products","one_sold","product_one_sold","solde",
     * "one_warehouse"})
     * @Groups({"product:read","product:write","delivery:write"})
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255, name="descipt")
     * @Serializer\Groups({"products","productByCodebarre","one_bill","list_products","sells_reports",
     * "report_cloture","one_sold","product_one_sold","solde","list_bills","one_warehouse","inventory"})
     * @Groups({
     *      "product:read","product:write","appointment:read","billDetail:read",
     *      "product_sample:read","delivery:write"
     * })
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=100, name="CodeLivraison", nullable=true)
     * @Serializer\Groups({"products","stock","list_products","sells_reports","report_cloture","one_sold",
     * "product_one_sold","solde","one_warehouse"})
     * @Groups({"product:read","product:write","delivery:write"})
     */
    private $codeLivraison;

    /**
     * @ORM\Column(type="string", length=15, name="codeWh",nullable=true)
     */
    private $codeWh;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"products","warehouses","stock","productByCodebarre","one_bill","list_products",
     * "bill_detail","warehouses","one_sold","product_one_sold","solde","list_bills","resume","stock_resume",
     * "one_warehouse","inventory","segments","type_stats"})
     * @Groups({"product:read","product:write","delivery:write"})
     */
    private $caa;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"products","warehouses","stock","productByCodebarre","list_products","bill_detail",
     * "warehouses","one_sold","product_one_sold","solde","customer","list_bills","resume","stock_resume",
     * "one_warehouse","segments","type_stats"})
     * @Groups({"product:read","product:write","delivery:write"})
     */
    private $pv;

    /**
     * @ORM\Column(type="datetime", name="date")
     * @Serializer\Groups({"products","list_products","resume","stock_resume","one_warehouse"})
     * @Groups({"product:read"})
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Serializer\Groups({"products","one_sold","product_one_sold","solde","one_warehouse"})
     * @Groups({"product:read","product:write","delivery:write"})
     */
    private $source;

    /**
     * @ORM\Column(type="integer",name="moveStatus")
     * @Serializer\Groups({"products","warehouses","stock","productByCodebarre","one_bill","product","list_products","warehouses","solde","one_warehouse"})
     * @Groups({"product:read","product:write"})
     */
    private $moveStatus;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_stock_add_in;

    /**
     * @ApiSubresource
     * @ORM\OneToOne(targetEntity="App\Entity\ProductStock", cascade={"persist", "remove"})
     * @Serializer\Groups({"products","warehouses","stock","productByCodebarre","one_bill","product","list_products","one_sold","product_one_sold","solde","resume","stock_resume","one_warehouse"})
     * @Groups({"product:read"})
     */
    private $stock;

    /**
     * @ApiSubresource
     * @ORM\OneToMany(targetEntity="App\Entity\BillDetails", mappedBy="product")
     * @Serializer\Groups({"list_products","stock","resume","stock_resume","segments","products"})
     */
    private $billDetails;

    /**
     * @ApiSubresource
     * @ORM\ManyToOne(targetEntity="App\Entity\Type", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"products","stock","productByCodebarre","one_bill","product","list_products",
     * "sells_reports","one_sold","product_one_sold","solde","customer","one_warehouse","inventory","warehouses" })
     * @Groups({"product:read","product:write","billDetail:read"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Warehouse", inversedBy="products")
     * @Serializer\Groups({"products","stock","product_one_sold","one_sold"})
     * @Groups({"product:read","product:write"})
     */
    private $warehouse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SoldOut", inversedBy="products")
     * @Serializer\Groups({"products","one_sold","product_one_sold","productByCodebarre","one_warehouse"})
     * @Groups({"product:read"})
     */
    private $sold;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InventoryProduct", mappedBy="product")
     */
    private $inventoryProducts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductCategory", inversedBy="products")
     * @Groups({"product:read","product:write"})
     */
    private $category;

    /**
     * @ApiSubresource
     * @ORM\ManyToOne(targetEntity="App\Entity\Brand", inversedBy="products")
     * @Groups({"product:read","product:write"})
     */
    private $brand;

    /**
     * @ApiSubresource
     * @ORM\OneToMany(targetEntity="App\Entity\ProductMovement", mappedBy="product")
     */
    private $productMovements;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Appointment", mappedBy="service")
     */
    private $appointments;

    /**
     * @ApiSubresource
     * @ORM\OneToMany(targetEntity="App\Entity\ProductSample", mappedBy="product", orphanRemoval=true)
     */
    private $samples;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Delivery", inversedBy="products")
     */
    private $delivery;
    
    /**
     * @Groups({"delivery:write"})
     */
    private $segment;

    /**
     * @Groups({"delivery:write"})
     */
    private $postType;

    public function __construct()
    {
        $this->billDetails = new ArrayCollection();
        $this->inventoryProducts = new ArrayCollection();
        $this->productMovements = new ArrayCollection();
        $this->appointments = new ArrayCollection();
        $this->samples = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCat(): ?string
    {
        return $this->cat;
    }

    public function setCat(string $cat): self
    {
        $this->cat = $cat;

        return $this;
    }

    public function getCodebarre(): ?string
    {
        return $this->codebarre;
    }

    public function setCodebarre(string $codebarre): self
    {
        $this->codebarre = $codebarre;

        return $this;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(?string $taille): self
    {
        $this->taille = $taille;

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

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCodeLivraison(): ?string
    {
        return $this->codeLivraison;
    }

    public function setCodeLivraison(string $codeLivraison): self
    {
        $this->codeLivraison = $codeLivraison;

        return $this;
    }

    public function getCodeWh(): ?string
    {
        return $this->codeWh;
    }

    public function setCodeWh(string $codeWh): self
    {
        $this->codeWh = $codeWh;

        return $this;
    }

    public function getCaa(): ?float
    {
        return $this->caa;
    }

    public function setCaa(float $caa): self
    {
        $this->caa = $caa;

        return $this;
    }

    public function getPv(): ?float
    {
        return $this->pv;
    }

    public function setPv(float $pv): self
    {
        $this->pv = $pv;

        return $this;
    }

    public function getMarge(): ?float
    {
        return $this->marge;
    }

    public function setMarge(float $marge): self
    {
        $this->marge = $marge;

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

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getMoveStatus(): ?int
    {
        return $this->moveStatus;
    }

    public function setMoveStatus(int $moveStatus): self
    {
        $this->moveStatus = $moveStatus;

        return $this;
    }

    public function getIdStockAddIn(): ?int
    {
        return $this->id_stock_add_in;
    }

    public function setIdStockAddIn(int $id_stock_add_in): self
    {
        $this->id_stock_add_in = $id_stock_add_in;

        return $this;
    }

    public function getStock(): ?ProductStock
    {
        return $this->stock;
    }

    public function setStock(?ProductStock $stock): self
    {
        $this->stock = $stock;

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
            $billDetail->setProduct($this);
        }

        return $this;
    }

    public function removeBillDetail(BillDetails $billDetail): self
    {
        if ($this->billDetails->contains($billDetail)) {
            $this->billDetails->removeElement($billDetail);
            // set the owning side to null (unless already changed)
            if ($billDetail->getProduct() === $this) {
                $billDetail->setProduct(null);
            }
        }

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getWarehouse(): ?Warehouse
    {
        return $this->warehouse;
    }

    public function setWarehouse(?Warehouse $warehouse): self
    {
        $this->warehouse = $warehouse;

        return $this;
    }

    public function getSold(): ?SoldOut
    {
        return $this->sold;
    }

    public function setSold(?SoldOut $sold): self
    {
        $this->sold = $sold;

        return $this;
    }

    /**
     * @return Collection|InventoryProduct[]
     */
    public function getInventoryProducts(): Collection
    {
        return $this->inventoryProducts;
    }

    public function addInventoryProduct(InventoryProduct $inventoryProduct): self
    {
        if (!$this->inventoryProducts->contains($inventoryProduct)) {
            $this->inventoryProducts[] = $inventoryProduct;
            $inventoryProduct->setProduct($this);
        }

        return $this;
    }

    public function removeInventoryProduct(InventoryProduct $inventoryProduct): self
    {
        if ($this->inventoryProducts->contains($inventoryProduct)) {
            $this->inventoryProducts->removeElement($inventoryProduct);
            // set the owning side to null (unless already changed)
            if ($inventoryProduct->getProduct() === $this) {
                $inventoryProduct->setProduct(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?ProductCategory
    {
        return $this->category;
    }

    public function setCategory(?ProductCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection|ProductMovement[]
     */
    public function getProductMovements(): Collection
    {
        return $this->productMovements;
    }

    public function addProductMovement(ProductMovement $productMovement): self
    {
        if (!$this->productMovements->contains($productMovement)) {
            $this->productMovements[] = $productMovement;
            $productMovement->setProduct($this);
        }

        return $this;
    }

    public function removeProductMovement(ProductMovement $productMovement): self
    {
        if ($this->productMovements->contains($productMovement)) {
            $this->productMovements->removeElement($productMovement);
            // set the owning side to null (unless already changed)
            if ($productMovement->getProduct() === $this) {
                $productMovement->setProduct(null);
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
            $appointment->setService($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->contains($appointment)) {
            $this->appointments->removeElement($appointment);
            // set the owning side to null (unless already changed)
            if ($appointment->getService() === $this) {
                $appointment->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductSample[]
     */
    public function getSamples(): Collection
    {
        return $this->samples;
    }

    public function addSample(ProductSample $sample): self
    {
        if (!$this->samples->contains($sample)) {
            $this->samples[] = $sample;
            $sample->setProduct($this);
        }

        return $this;
    }

    public function removeSample(ProductSample $sample): self
    {
        if ($this->samples->contains($sample)) {
            $this->samples->removeElement($sample);
            // set the owning side to null (unless already changed)
            if ($sample->getProduct() === $this) {
                $sample->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"product:read"})
     */
    public function getAvailableSample()
    {
        foreach($this->samples as $sample)
        {
            if($sample->getAvailable()){
                return $sample;
            }
        }
        return null;
    }

    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }

    public function setDelivery(?Delivery $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }
    
    public function getSegment(): ?string
    {
        return $this->segment;
    }

    public function setSegment(?string $segment)
    {
        $this->segment = $segment;

        return $this;
    }
    
    public function getPostType(): ?string
    {
        return $this->postType;
    }

    public function setPostType(?string $postType)
    {
        $this->postType = $postType;

        return $this;
    }
}
