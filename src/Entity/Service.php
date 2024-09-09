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
    
    public function __construct()
    {
        //$this->startedAt = new \DateTimeImmutable("NOW");
        //$this->endedAt = new \DateTimeImmutable("NOW");
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

    public function setTime(int $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
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
}
