<?php

namespace App\Entity;

use App\Repository\CategoryFaqRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryFaqRepository::class)
 */
class CategoryFaq
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=ArticleFaq::class, mappedBy="categoryFaq")
     */
    private $articleFaqs;

    public function __construct()
    {
        $this->articleFaqs = new ArrayCollection();
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
     * @return Collection|ArticleFaq[]
     */
    public function getArticleFaqs(): Collection
    {
        return $this->articleFaqs;
    }

    public function addArticleFaq(ArticleFaq $articleFaq): self
    {
        if (!$this->articleFaqs->contains($articleFaq)) {
            $this->articleFaqs[] = $articleFaq;
            $articleFaq->setCategoryFaq($this);
        }

        return $this;
    }

    public function removeArticleFaq(ArticleFaq $articleFaq): self
    {
        if ($this->articleFaqs->removeElement($articleFaq)) {
            // set the owning side to null (unless already changed)
            if ($articleFaq->getCategoryFaq() === $this) {
                $articleFaq->setCategoryFaq(null);
            }
        }

        return $this;
    }
}
