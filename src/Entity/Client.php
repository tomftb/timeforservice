<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private string $status;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageFilename = null;

    #[ORM\Column(nullable: true)]
    private ?float $hourlyRate = null;

    #[ORM\Column(nullable: true)]
    private ?float $kilometerRate = null;

    /**
     * @var Collection<int, ClientClassificationOfActivities>
     */
    #[ORM\OneToMany(targetEntity: ClientClassificationOfActivities::class, mappedBy: 'Client')]
    private Collection $clientClassificationOfActivities;
    
    public function __construct()
    {
        $this->status='NEW';
        $this->clientClassificationOfActivities = new ArrayCollection();
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

    public function getNin(): ?string
    {
        return $this->nin;
    }

    public function setNin(string $nin): static
    {
        $this->nin = $nin;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getImageFilename(): ?string
    {
        if(!$this->imageFilename){
            $this->imageFilename='blank_logo.png';
        }
        return $this->imageFilename;
    }

    public function setImageFilename(?string $imageFilename): static
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }

    public function getHourlyRate(): ?float
    {
        return $this->hourlyRate;
    }

    public function setHourlyRate(?float $hourlyRate): static
    {
        $this->hourlyRate = $hourlyRate;
        return $this;
    }

    public function getKilometerRate(): ?float
    {
        return $this->kilometerRate;
    }

    public function setKilometerRate(?float $kilometerRate): static
    {
        $this->kilometerRate = $kilometerRate;
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
            $clientClassificationOfActivity->setClient($this);
        }

        return $this;
    }

    public function removeClientClassificationOfActivity(ClientClassificationOfActivities $clientClassificationOfActivity): static
    {
        if ($this->clientClassificationOfActivities->removeElement($clientClassificationOfActivity)) {
            // set the owning side to null (unless already changed)
            if ($clientClassificationOfActivity->getClient() === $this) {
                $clientClassificationOfActivity->setClient(null);
            }
        }

        return $this;
    }
}
