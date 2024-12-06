<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $capacity = null;

    #[ORM\ManyToOne(targetEntity: Etablishment::class, inversedBy: 'rooms')]
    private ?Etablishment $etablishment = null;

    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[ORM\JoinTable(name:'room_tag')]
    private Collection $tags;

    #[ORM\OneToMany(mappedBy: 'room', targetEntity: Event::class)]
    private ?Collection $events = null;

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

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getEtablishment(): ?Etablishment
    {
        return $this->etablishment;
    }
    public function setEtablishment(?Etablishment $etablishment): static
    {
        $this->etablishment = $etablishment;
        return $this;
    }

    public function getTags () : Collection
    {
        return $this->tags;
    }
    public function setTags(Collection $tags): static
    {
        $this->tags = $tags;
        return $this;
    }

    public function getEvents () : Collection
    {
        return $this->events;
    }
}
