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
 *      subresourceOperations={
 *          "type_segment"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"type:read"}}
 *          },
 *          "product_department"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"product_department:read"}}
 *          },
 *      },
 *      normalizationContext={"groups"={"segment:read"}},
 *      denormalizationContext={"groups"={"segment:write"}}
 * )
 * @ApiFilter(
 *      SearchFilter::Class,
 *      properties={
 *          "department": "exact",
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\SegmentRepository")
 */
class Segment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"segments","products","one_warehouse","stock","customer","list_bills","inventory"})
     * @Groups({"segment:read","product_department:read","type:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,name="label")
     * @Serializer\Groups({"segments","products","one_warehouse","stock","list_products","sells_reports","one_sold",
     * "product_one_sold","customer","list_bills","inventory"})
     * @Groups({"segment:read","segment:write","product_department:read","type:read"})
     */
    private $name;

    /**
     * ApiSubresource
     * @ORM\OneToMany(targetEntity="App\Entity\Type", mappedBy="segment")
     * @Serializer\Groups({"segments"})
     */
    private $types;

    /**
     * @ApiSubresource
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductDepartment", inversedBy="segments")
     * @Groups({"segment:read","segment:write"})
     */
    private $department;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    public function __construct()
    {
        $this->types = new ArrayCollection();
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

    /**
     * @return Collection|Type[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
            $type->setSegment($this);
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        if ($this->types->contains($type)) {
            $this->types->removeElement($type);
            // set the owning side to null (unless already changed)
            if ($type->getSegment() === $this) {
                $type->setSegment(null);
            }
        }

        return $this;
    }
    public function getDepartment(): ?ProductDepartment
    {
        return $this->department;
    }

    public function setDepartment(?ProductDepartment $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }
}
