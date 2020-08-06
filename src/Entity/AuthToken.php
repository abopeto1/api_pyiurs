<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthTokenRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="auth_tokens_value_unique", columns={"value"})})
 */
class AuthToken
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"auth_tokens"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"auth_tokens"})
     */
    private $value;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"auth_tokens"})
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="authTokens")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"auth_tokens"})
     */
    private $user;

    /**
     * @Serializer\Groups({"auth_tokens"})
     */
    public $taux;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
