<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TauxRepository")
 */
class Taux
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"auth_tokens"})
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"auth_tokens"})
     */
    private $taux;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaux(): ?float
    {
        return $this->taux;
    }

    public function setTaux(float $taux): self
    {
        $this->taux = $taux;

        return $this;
    }
}
