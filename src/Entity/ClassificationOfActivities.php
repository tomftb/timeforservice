<?php

namespace App\Entity;

use App\Repository\ClassificationOfActivitiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ClassificationOfActivitiesRepository::class)]
class ClassificationOfActivities
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $code = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    private ?string $unit = null;

    /**
     * @var Collection<int, ClientClassificationOfActivities>
     */
    #[ORM\OneToMany(targetEntity: ClientClassificationOfActivities::class, mappedBy: 'classification')]
    private Collection $clientClassificationOfActivities;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\OneToMany(targetEntity: Service::class, mappedBy: 'classificationOfActivities')]
    private Collection $services;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    public function __construct()
    {
        $this->clientClassificationOfActivities = new ArrayCollection();
        $this->services = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
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

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection<int, ClientClassificationOfActivities>
     */
    public function getClientClassificationOfActivities(): Collection
    {
        return $this->clientClassificationOfActivities;
    }

    public function addClientClassificationOfActivity(ClientClassificationOfActivities $clientClassificationOfActivity): static
    {
        if (!$this->clientClassificationOfActivities->contains($clientClassificationOfActivity)) {
            $this->clientClassificationOfActivities->add($clientClassificationOfActivity);
            $clientClassificationOfActivity->setClassification($this);
        }

        return $this;
    }

    public function removeClientClassificationOfActivity(ClientClassificationOfActivities $clientClassificationOfActivity): static
    {
        if ($this->clientClassificationOfActivities->removeElement($clientClassificationOfActivity)) {
            // set the owning side to null (unless already changed)
            if ($clientClassificationOfActivity->getClassification() === $this) {
                $clientClassificationOfActivity->setClassification(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setClassificationOfActivities($this);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getClassificationOfActivities() === $this) {
                $service->setClassificationOfActivities(null);
            }
        }

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }
}
