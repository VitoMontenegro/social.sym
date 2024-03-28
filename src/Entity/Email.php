<?php

namespace App\Entity;

use App\Repository\EmailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmailRepository::class)]
#[ORM\Index(columns: ['email'], name: 'email_x')]
#[ORM\UniqueConstraint(name: 'unique_email', columns: ['email'])]
class Email
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id = 0;

    #[ORM\Column]
    private ?int $authid = null;

    #[ORM\Column]
    private ?int $cod = null;

    #[ORM\Column(length: 50)]
    private ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCod(): ?int
    {
        return $this->cod;
    }

    public function setCod(int $cod): static
    {
        $this->cod = $cod;
        return $this;
    }

    public function getAuthid(): ?int
    {
        return $this->authid;
    }

    public function setAuthid(int $authid): static
    {
        $this->authid = $authid;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }
}
