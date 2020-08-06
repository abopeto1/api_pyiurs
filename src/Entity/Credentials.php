<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Credentials
{
    /**
     * @Assert\NotBlank
     */
    private $login;

    /**
     * @Assert\NotBlank
     */
    private $password;

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
