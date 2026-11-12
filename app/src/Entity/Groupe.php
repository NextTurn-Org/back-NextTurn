<?php

namespace App\Entity;

use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
class Groupe
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 7)]
    private ?string $colorHex = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * @var Collection<int, UserGroupe>
     */
    #[ORM\OneToMany(targetEntity: UserGroupe::class, mappedBy: 'groupe')]
    private Collection $userGroupes;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'groupe')]
    private Collection $events;

    public function __construct()
    {
        $this->userGroupes = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getColorHex(): ?string
    {
        return $this->colorHex;
    }

    public function setColorHex(string $colorHex): static
    {
        $this->colorHex = $colorHex;

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

    /**
     * @return Collection<int, UserGroupe>
     */
    public function getUserGroupes(): Collection
    {
        return $this->userGroupes;
    }

    public function addUserGroupe(UserGroupe $userGroupe): static
    {
        if (!$this->userGroupes->contains($userGroupe)) {
            $this->userGroupes->add($userGroupe);
            $userGroupe->setGroupe($this);
        }

        return $this;
    }

    public function removeUserGroupe(UserGroupe $userGroupe): static
    {
        if ($this->userGroupes->removeElement($userGroupe)) {
            // set the owning side to null (unless already changed)
            if ($userGroupe->getGroupe() === $this) {
                $userGroupe->setGroupe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setGroupe($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getGroupe() === $this) {
                $event->setGroupe(null);
            }
        }

        return $this;
    }
}
