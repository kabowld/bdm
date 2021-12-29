<?php

namespace App\Entity;

use App\Repository\CategoryStateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoryStateRepository::class)
 */
class CategoryState
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @Assert\NotBlank(message="Saisir un type de categorie")
     * @Assert\Choice({"normal", "style"})
     * @ORM\Column(type="string", length=6)
     */
    private string $title;

    /**
     * @ORM\OneToMany(targetEntity=State::class, mappedBy="categoryState")
     */
    private Collection $states;

    public function __construct()
    {
        $this->states = new ArrayCollection();
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

    /**
     * @return Collection|State[]
     */
    public function getStates(): Collection
    {
        return $this->states;
    }

    public function addState(State $state): self
    {
        if (!$this->states->contains($state)) {
            $this->states[] = $state;
            $state->setCategoryState($this);
        }

        return $this;
    }

    public function removeState(State $state): self
    {
        if ($this->states->removeElement($state)) {
            // set the owning side to null (unless already changed)
            if ($state->getCategoryState() === $this) {
                $state->setCategoryState(null);
            }
        }

        return $this;
    }
}
