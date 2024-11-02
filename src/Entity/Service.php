<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    use TimestampableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endedAt = null;

    #[ORM\Column]
    private ?int $time = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    //#[ORM\ManyToOne('ClientPoint',null,'EAGER')]
    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    private ?ClientPoint $clientPoint = null;

    //#[ORM\ManyToOne('User',null,'EAGER')]
    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?float $route = null;

    #[ORM\Column(nullable: true)]
    private ?float $rate = null;

    #[ORM\Column(nullable: true)]
    private ?float $cost = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $unit = null;

    #[ORM\Column(nullable: true)]
    private ?float $routePrice = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    private ?ClassificationOfActivities $classificationOfActivities = null;
    
    public function __construct()
    {
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTimeImmutable $endedAt): static
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(?int $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getClientPoint(): ?ClientPoint
    {
        return $this->clientPoint;
    }

    public function setClientPoint(?ClientPoint $clientPoint): static
    {
        $this->clientPoint = $clientPoint;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getRoute(): ?float
    {
        return $this->route;
    }

    public function setRoute(?float $route): static
    {
        $this->route = $route;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(?float $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

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

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getRoutePrice(): ?float
    {
        return $this->routePrice;
    }

    public function setRoutePrice(?float $routePrice): static
    {
        $this->routePrice = $routePrice;

        return $this;
    }
    
    public function getClassificationOfActivities(): ?ClassificationOfActivities
    {
        return $this->classificationOfActivities;
    }
    
    public function setClassificationOfActivities(?ClassificationOfActivities $classificationOfActivities): static
    {
        $this->classificationOfActivities = $classificationOfActivities;

        return $this;
    }
}
