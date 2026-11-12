<?php

namespace App\Entity;

use App\Repository\UserGroupeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserGroupeRepository::class)]
class UserGroupe
{
    // Clé composite (user_id, groupe_id)
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'userGroupes')]
    #[ORM\JoinColumn(name: 'user_id', nullable: false)]
    private ?User $users = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'userGroupes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Groupe $groupe = null;

    #[ORM\ManyToOne(inversedBy: 'userGroupes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GroupeRole $role = null;

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): static
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function getRole(): ?GroupeRole
    {
        return $this->role;
    }

    public function setRole(?GroupeRole $role): static
    {
        $this->role = $role;

        return $this;
    }
}
