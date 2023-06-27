<?php

namespace App\Entity;

use App\Repository\AnnouncementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnnouncementRepository::class)]
class Announcement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    protected ?string $title = null;

    #[ORM\Column]
    protected ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    protected ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'announcements')]
    #[ORM\JoinColumn(nullable: false)]
    protected ?Announcer $announcer = null;

    #[ORM\OneToMany(mappedBy: 'announcement', targetEntity: Dog::class, cascade: ['persist', 'remove'], orphanRemoval: true)]

    #[Assert\Count(min: 1)]
    protected Collection $dogs;

    #[ORM\OneToMany(mappedBy: 'announcement', targetEntity: Request::class)]
    protected Collection $requests;

    #[ORM\Column(type: Types::TEXT)]
    protected ?string $generalInformation = null;

    public function __construct()
    {
        $this->dogs = new ArrayCollection();
        $this->requests = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAnnouncer(): ?Announcer
    {
        return $this->announcer;
    }

    public function setAnnouncer(?Announcer $announcer): self
    {
        $this->announcer = $announcer;

        return $this;
    }

    /**
     * @return Collection<int, Dog>
     */
    public function getDogs(): Collection
    {
        return $this->dogs;
    }

    public function addDog(Dog $dog): self
    {
        if (!$this->dogs->contains($dog)) {
            $this->dogs->add($dog);
            $dog->setAnnouncement($this);
        }

        return $this;
    }

    public function removeDog(Dog $dog): self
    {
        if ($this->dogs->removeElement($dog)) {
            // set the owning side to null (unless already changed)
            if ($dog->getAnnouncement() === $this) {
                $dog->setAnnouncement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): self
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->setAnnouncement($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): self
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getAnnouncement() === $this) {
                $request->setAnnouncement(null);
            }
        }

        return $this;
    }

    public function getGeneralInformation(): ?string
    {
        return $this->generalInformation;
    }

    public function setGeneralInformation(?string $generalInformation): self
    {
        $this->generalInformation = $generalInformation;

        return $this;
    }

    // Méthode pour refactoriser l'affichage de mon annonce
    public function getDogsImages(bool $onlyNotAdopted = false): array
    {
        $images = [];

        foreach ($this->getDogs() as $dog) {
            if (false === $onlyNotAdopted || !$dog->getIsAdopted()) {
                foreach ($dog->getImages() as $image) {
                    $images[] = $image;
                }
            }
        }

        return $images;
    }

    public function getDogsRaces(): array
    {
        $races = [];

        foreach ($this->getDogs() as $dog) {
            foreach ($dog->getRaces() as $race) {
                $races[] = $race;
            }
        }

        return $races;
    }

    public function isAnnouncementClosed(): bool
    {
        if (0 == $this->getDogs()->count()) {
            return false;
        }

        $adoptedDogs = $this->getDogs()->filter(function (Dog $dog) {
            return $dog->getIsAdopted();
        });

        return $adoptedDogs->count() == $this->getDogs()->count();
    }

    /**
     * @return Collection<int, Dog>
     */
    public function getAdoptableDogs(): Collection
    {
        $adoptableDogs = $this->getDogs()->filter(function (Dog $dog) {
            return !$dog->getIsAdopted();
        });

        return $adoptableDogs;
    }

    /**
     * @return Collection<int, Dog>
     */
    public function getNotAdoptableDogs(): Collection
    {
        $adoptableDogs = $this->getDogs()->filter(function (Dog $dog) {
            return $dog->getIsAdopted();
        });

        return $adoptableDogs;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
