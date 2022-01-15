<?php

namespace App\Entity;

use App\Repository\RubriqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RubriqueRepository::class)
 * @UniqueEntity(fields={"title"}, message="Il existe déjà un titre avec cette rubrque!")
 */
class Rubrique
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @Assert\NotBlank(message="Le titre de la rubrique ne doit pas être vide !")
     * @Assert\Length(max=50, maxMessage="Le nombre de caractère maximum ne doit pas dépasser {{ limit }}")
     * @ORM\Column(type="string", length=50)
     */
    private string $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $description;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="rubrique")
     */
    private Collection $categories;

    /**
     * @ORM\OneToOne(targetEntity=FilePicture::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private ?FilePicture $image;

    /**
     * @Assert\NotBlank(message="Le slug de la rubrique ne doit pas être vide !")
     * @Assert\Length(max=50, maxMessage="Le nombre de caractère maximum ne doit pas dépasser {{ limit }}")
     * @ORM\Column(type="string", length=50)
     */
    private string $slug;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setRubrique($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getRubrique() === $this) {
                $category->setRubrique(null);
            }
        }

        return $this;
    }

    public function getImage(): ?FilePicture
    {
        return $this->image;
    }

    public function setImage(?FilePicture $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }


}
