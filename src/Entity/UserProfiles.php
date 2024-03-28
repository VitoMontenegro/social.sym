<?php

namespace App\Entity;

use App\Repository\UserProfilesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserProfilesRepository::class)]
#[ORM\Index(columns: ['id'], name: 'id_x')]
#[ORM\UniqueConstraint(name: 'unique_authid', columns: ['authid'])]
class UserProfiles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $authid = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string  $birthday = null;

    #[ORM\Column(length: 250)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $surname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $patronymic = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;
        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(?string $birthday): static
    {
        $this->birthday = $birthday;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): static
    {
        $this->surname = $surname;
        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(?string $patronymic): static
    {
        $this->patronymic = $patronymic;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }
}
