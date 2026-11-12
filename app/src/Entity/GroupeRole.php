<?php

namespace App\Entity;

use App\Repository\GroupeRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: GroupeRoleRepository::class)]
class GroupeRole
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $permissions = null;

    /**
     * @var Collection<int, UserGroupe>
     */
    #[ORM\OneToMany(targetEntity: UserGroupe::class, mappedBy: 'role')]
    private Collection $userGroupes;

    public function __construct()
    {
        $this->userGroupes = new ArrayCollection();
    }

    public function getId(): ?Uuid
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

    public function getPermissions(): ?string
    {
        return $this->permissions;
    }

    public function setPermissions(string $permissions): static
    {
        $this->permissions = $permissions;

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
            $userGroupe->setRole($this);
        }

        return $this;
    }

    public function removeUserGroupe(UserGroupe $userGroupe): static
    {
        if ($this->userGroupes->removeElement($userGroupe)) {
            // set the owning side to null (unless already changed)
            if ($userGroupe->getRole() === $this) {
                $userGroupe->setRole(null);
            }
        }

        return $this;
    }
}
