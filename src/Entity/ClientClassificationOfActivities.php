<?php

namespace App\Entity;

use App\Repository\ClientClassificationOfActivitiesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ClientClassificationOfActivitiesRepository::class)]
class ClientClassificationOfActivities
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'clientClassificationOfActivities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'clientClassificationOfActivities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ClassificationOfActivities $classification = null;

    protected Collection $classificationOfActivities;
    
    public function __construct()
    {
        $this->classificationOfActivities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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

    public function getClassification(): ?ClassificationOfActivities
    {
        return $this->classification;
    }

    public function setClassification(?ClassificationOfActivities $classification): static
    {
        $this->classification = $classification;

        return $this;
    }
    public function getClassificationOfActivities(): Collection
    {
        return $this->classificationOfActivities;
    }
}
