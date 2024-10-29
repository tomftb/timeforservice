<?php

namespace App\Entity;

use App\Repository\ClassificationOfActivitiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassificationOfActivitiesRepository::class)]
class ClassificationOfActivities
{
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

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, ClientClassificationOfActivities>
     */
    #[ORM\OneToMany(targetEntity: ClientClassificationOfActivities::class, mappedBy: 'classification')]
    private Collection $clientClassificationOfActivities;

    public function __construct()
    {
        $this->clientClassificationOfActivities = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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
}
