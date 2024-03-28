<?php

namespace App\Entity;

use App\Repository\DevicesRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevicesRepository::class)]
#[ORM\Index(columns: ['uuid'], name: 'uuid_x')]
#[ORM\UniqueConstraint(name: 'unique_devices', columns: ['authid', 'uuid'])]
class Devices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private DateTime $updated;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

    #[ORM\Column]
    private ?int $authid = null;

    #[ORM\Column]
    private ?bool $blocked = null;

    public function getBlocked(): ?bool
    {
        return $this->blocked;
    }

    public function setBlocked(?bool $blocked): static
    {
        $this->blocked = $blocked;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

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

    public function setUpdated(): static
    {
        $this->updated = new DateTime("now");
        return $this;
    }

    public function getUpdated(): DateTime
    {
        return $this->updated;
    }
}
