<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  subresourceOperations={
 *      "segment_department"={
 *          "method"="GET",
 *          "normalization_context"={"groups"={"segment:read"}}
 *      }
 *  },
 *  normalizationContext={ "groups"={"product_department:read"}},
 *  denormalizationContext={ "groups"={"product_department:write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ProductDepartmentRepository")
 */
class ProductDepartment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"product_department:read","segment:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product_department:read","product_department:write","segment:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"product_department:read","product_department:write"})
     */
    private $description;

    /**
     * ApiSubresource
     * @ORM\OneToMany(targetEntity="App\Entity\Segment", mappedBy="department")
     * @Groups({"product_department:read"})
     */
    private $segments;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->segments = new ArrayCollection();
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
     * @return Collection|Segment[]
     */
    public function getSegments(): Collection
    {
        return $this->segments;
    }

    public function addSegment(Segment $segment): self
    {
        if (!$this->segments->contains($segment)) {
            $this->segments[] = $segment;
            $segment->setDepartment($this);
        }

        return $this;
    }

    public function removeSegment(Segment $segment): self
    {
        if ($this->segments->contains($segment)) {
            $this->segments->removeElement($segment);
            // set the owning side to null (unless already changed)
            if ($segment->getDepartment() === $this) {
                $segment->setDepartment(null);
            }
        }

        return $this;
    }
}
