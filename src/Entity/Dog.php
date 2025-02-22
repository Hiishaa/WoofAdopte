<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\DogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DogRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
    ],
)]
class Dog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["read", "read:Announcement"])]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["read", "read:Announcement"])]
    protected ?string $name = null;

    #[ORM\Column]
    #[Groups(["read", "read:Announcement"])]
    protected ?bool $isLof = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["read", "read:Announcement"])]
    protected ?string $background = null;

    #[ORM\Column]
    #[Groups(["read", "read:Announcement"])]
    protected ?bool $isPetFriendly = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["read", "read:Announcement"])]
    protected ?string $description = null;

    #[ORM\Column]
    #[Groups(["read", "read:Announcement"])]
    protected ?bool $isAdopted = false;

    #[ORM\ManyToOne(inversedBy: 'dogs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["read", "read:Announcement"])]
    protected ?Announcement $announcement = null;

    #[ORM\OneToMany(mappedBy: 'dog', targetEntity: Image::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Assert\Count(min: 0)]
    #[Groups(["read", "read:Announcement"])]
    protected Collection $images;

    #[ORM\ManyToMany(targetEntity: Race::class, inversedBy: 'dogs')]
    #[Groups(["read", "read:Announcement"])]
    protected Collection $races;

    #[ORM\ManyToMany(targetEntity: Request::class, mappedBy: 'dogs')]
    #[Groups(["read", "read:Announcement"])]
    protected Collection $requests;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->races = new ArrayCollection();
        $this->requests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsLof(): ?bool
    {
        return $this->isLof;
    }

    public function setIsLof(bool $isLof): self
    {
        $this->isLof = $isLof;

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function setBackground(string $background): self
    {
        $this->background = $background;

        return $this;
    }

    public function getIsPetFriendly(): ?bool
    {
        return $this->isPetFriendly;
    }

    public function setIsPetFriendly(bool $isPetFriendly): self
    {
        $this->isPetFriendly = $isPetFriendly;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsAdopted(): ?bool
    {
        return $this->isAdopted;
    }

    public function setIsAdopted(bool $isAdopted): self
    {
        $this->isAdopted = $isAdopted;

        return $this;
    }

    public function getAnnouncement(): ?Announcement
    {
        return $this->announcement;
    }

    public function setAnnouncement(?Announcement $announcement): self
    {
        $this->announcement = $announcement;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setDog($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getDog() === $this) {
                $image->setDog(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Race>
     */
    public function getRaces(): Collection
    {
        return $this->races;
    }

    public function addRace(Race $race): self
    {
        if (!$this->races->contains($race)) {
            $this->races->add($race);
        }

        return $this;
    }

    public function removeRace(Race $race): self
    {
        $this->races->removeElement($race);

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
            $request->addDog($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): self
    {
        if ($this->requests->removeElement($request)) {
            $request->removeDog($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}