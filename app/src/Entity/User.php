<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $passwordHash = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToOne(mappedBy: 'users', cascade: ['persist', 'remove'])]
    private ?UserDetails $userDetails = null;

    #[ORM\OneToOne(mappedBy: 'users', cascade: ['persist', 'remove'])]
    private ?Role $role = null;

    /**
     * @var Collection<int, UserGroupe>
     */
    #[ORM\OneToMany(targetEntity: UserGroupe::class, mappedBy: 'users')]
    private Collection $userGroupes;

    /**
     * @var Collection<int, EventAttendee>
     */
    #[ORM\OneToMany(targetEntity: EventAttendee::class, mappedBy: 'users')]
    private Collection $eventAttendees;

    public function __construct()
    {
        $this->userGroupes = new ArrayCollection();
        $this->eventAttendees = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): static
    {
        $this->passwordHash = $passwordHash;

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

    public function getUserDetails(): ?UserDetails
    {
        return $this->userDetails;
    }

    public function setUserDetails(UserDetails $userDetails): static
    {
        // set the owning side of the relation if necessary
        if ($userDetails->getUsers() !== $this) {
            $userDetails->setUsers($this);
        }

        $this->userDetails = $userDetails;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(Role $role): static
    {
        // set the owning side of the relation if necessary
        if ($role->getUsers() !== $this) {
            $role->setUsers($this);
        }

        $this->role = $role;

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
            $userGroupe->setUsers($this);
        }

        return $this;
    }

    public function removeUserGroupe(UserGroupe $userGroupe): static
    {
        if ($this->userGroupes->removeElement($userGroupe)) {
            // set the owning side to null (unless already changed)
            if ($userGroupe->getUsers() === $this) {
                $userGroupe->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EventAttendee>
     */
    public function getEventAttendees(): Collection
    {
        return $this->eventAttendees;
    }

    public function addEventAttendee(EventAttendee $eventAttendee): static
    {
        if (!$this->eventAttendees->contains($eventAttendee)) {
            $this->eventAttendees->add($eventAttendee);
            $eventAttendee->setUsers($this);
        }

        return $this;
    }

    public function removeEventAttendee(EventAttendee $eventAttendee): static
    {
        if ($this->eventAttendees->removeElement($eventAttendee)) {
            // set the owning side to null (unless already changed)
            if ($eventAttendee->getUsers() === $this) {
                $eventAttendee->setUsers(null);
            }
        }

        return $this;
    }
}
