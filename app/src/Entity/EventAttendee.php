<?php

namespace App\Entity;

use App\Repository\EventAttendeeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventAttendeeRepository::class)]
class EventAttendee
{
    // Clé composite (event_id, user_id)
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'eventAttendees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'eventAttendees')]
    #[ORM\JoinColumn(name: 'user_id', nullable: false)]
    private ?User $users = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
