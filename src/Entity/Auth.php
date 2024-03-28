<?php

namespace App\Entity;

use App\Repository\AuthRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthRepository::class)]

class Auth
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id = 0;

    #[ORM\Column(length: 255)]
    private ?string $salt = null;

    #[ORM\Column]
    private ?bool $blocked = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(string $salt): static
    {
        $this->salt = $salt;

        return $this;
    }

    public function getBlocked(): ?bool
    {
        return $this->blocked;
    }

    public function setBlocked(?bool $blocked): static
    {
        $this->blocked = $blocked;
        return $this;
    }

}
