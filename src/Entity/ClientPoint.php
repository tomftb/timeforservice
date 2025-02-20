<?php

namespace App\Entity;

use App\Model\YesOrNoEnum;
use App\Repository\ClientPointRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;


#[ORM\Entity(repositoryClass: ClientPointRepository::class)]
class ClientPoint
{
    use TimestampableEntity;
     
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column(length: 255)]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255)]
    private ?string $town = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;
        
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\Column(nullable: true)]
    private ?float $distance = null;

    #[ORM\Column(nullable: true, enumType: YesOrNoEnum::class)]
    private ?YesOrNoEnum $sendNotify = null;

    #[ORM\Column(enumType: YesOrNoEnum::class)]
    private ?YesOrNoEnum $active = null;

    #[ORM\Column(enumType: YesOrNoEnum::class)]
    private ?YesOrNoEnum $deleted = null;

    public function __construct()
    {
        $this->active=YesOrNoEnum::YES;
        $this->deleted=YesOrNoEnum::NO;
    }

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;
        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(?float $distance): static
    {
        $this->distance = $distance;

        return $this;
    }

    public function getSendNotify(): ?YesOrNoEnum
    {
        return $this->sendNotify;
    }

    public function setSendNotify(?YesOrNoEnum $sendNotify): static
    {
        $this->sendNotify = $sendNotify;

        return $this;
    }

    public function getActive(): ?YesOrNoEnum
    {
        return $this->active;
    }

    public function setActive(YesOrNoEnum $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getDeleted(): ?YesOrNoEnum
    {
        return $this->deleted;
    }

    public function setDeleted(YesOrNoEnum $deleted): static
    {
        $this->deleted = $deleted;

        return $this;
    }
}
