<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StateRepository::class)
 */
class State
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @Assert\NotBlank(message="Le titre doit être définit !")
     * @Assert\Length(max=50, maxMessage="Le titre d'un état dépasse la limite autorisée !")
     * @ORM\Column(type="string", length=50)
     */
    private string $title;

    /**
     * @Assert\NotBlank(message="La description doit être définit !")
     * @ORM\Column(type="text")
     */
    private string $description;

    /**
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity=CategoryState::class, inversedBy="states", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoryState;

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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategoryState(): ?CategoryState
    {
        return $this->categoryState;
    }

    public function setCategoryState(?CategoryState $categoryState): self
    {
        $this->categoryState = $categoryState;

        return $this;
    }
}
