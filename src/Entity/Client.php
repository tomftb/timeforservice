<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    
    use TimestampableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $street = null;

    #[ORM\Column(length: 100)]
    private ?string $zipCode = null;

    #[ORM\Column(length: 100)]
    private ?string $town = null;

    #[ORM\Column(length: 255)]
    private ?string $nin = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): static
    {
        $this->town = $town;

        return $this;
    }

    public function getNin(): ?string
    {
        return $this->nin;
    }

    public function setNin(string $nin): static
    {
        $this->nin = $nin;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
