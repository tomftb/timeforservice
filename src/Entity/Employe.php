<?php

namespace App\Entity;

use App\Model\YesOrNoEnum;
use App\Repository\EmployeRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe
{
    use TimestampableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(enumType: YesOrNoEnum::class)]
    private ?YesOrNoEnum $active = null;

    public function __construct()
    {
        $this->active=YesOrNoEnum::YES;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

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

}
