<?php

namespace App\Entity;

use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @UniqueEntity(fields={"title"}, message="Il exsite déjà un titre similaire")
 * @UniqueEntity(fields={"role"}, message="Il existe déjà un rôle similaire")
 */
class Groupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Assert\NotBlank(message="Le titre ne doit pas être vide !")
     * @Assert\Length(max=50, maxMessage="Le titre doit comporter {{ limit }} caractères maximum !")
     * @ORM\Column(type="string", length=50)
     */
    private string $title;

    /**
     * @Assert\NotBlank(message="Le rôle ne doit pas être vide !")
     * @Assert\Length(max=50, maxMessage="Le rôle doit comporter {{ limit }} caractères maximum !")
     * @Assert\Regex(pattern="/^ROLE_/", message="Syntaxe incorrecte pour le rôle !")
     * @ORM\Column(type="string", length=50)
     */
    private string $role;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="groupe")
     */
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setGroupe($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getGroupe() === $this) {
                $user->setGroupe(null);
            }
        }

        return $this;
    }
}
