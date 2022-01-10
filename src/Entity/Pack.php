<?php

namespace App\Entity;

use App\Repository\PackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PackRepository::class)
 */
class Pack
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @Assert\Length(max=100, maxMessage="Le titre ne doit pas dépasser {{ limit }} caractères !")
     * @Assert\NotBlank(message="Le titre ne doit pas être vide !")
     * @ORM\Column(type="string", length=100)
     */
    private string $title;

    /**
     * @Assert\NotBlank(message="La description ne doit pas être vide !")
     * @ORM\Column(type="text")
     */
    private string $description;

    /**
     * @Assert\Length(max=10, maxMessage="Le prix ne doit pas dépasser {{ limit }} caractères !")
     * @Assert\NotBlank(message="Le prix ne doit pas être vide !")
     * @ORM\Column(type="string", length=10)
     */
    private string $price;

    /**
     * @Assert\GreaterThan(value=0, message="Le nombre de jours doit être supérieur à zéro")
     * @ORM\Column(type="smallint")
     */
    private int $days;

    /**
     * @Assert\Length(max=50, maxMessage="Le prix ne doit pas dépasser {{ limit }} caractères !")
     * @Assert\NotBlank(message="Le prix/Jour ne doit pas être vide !")
     * @ORM\Column(type="string", length=50)
     */
    private string $priceByDay;

    /**
     * @ORM\OneToMany(targetEntity=DetailsPack::class, mappedBy="pack", orphanRemoval=true)
     */
    private Collection $detailsPacks;

    /**
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="pack")
     */
    private Collection $annonces;

    /**
     * @ORM\OneToOne(targetEntity=FilePicture::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?FilePicture $image;

    public function __construct()
    {
        $this->detailsPacks = new ArrayCollection();
        $this->annonces = new ArrayCollection();
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDays(): ?int
    {
        return $this->days;
    }

    public function setDays(int $days): self
    {
        $this->days = $days;

        return $this;
    }

    public function getPriceByDay(): ?string
    {
        return $this->priceByDay;
    }

    public function setPriceByDay(string $priceByDay): self
    {
        $this->priceByDay = $priceByDay;

        return $this;
    }

    /**
     * @return Collection|DetailsPack[]
     */
    public function getDetailsPacks(): Collection
    {
        return $this->detailsPacks;
    }

    public function addDetailsPack(DetailsPack $detailsPack): self
    {
        if (!$this->detailsPacks->contains($detailsPack)) {
            $this->detailsPacks[] = $detailsPack;
            $detailsPack->setPack($this);
        }

        return $this;
    }

    public function removeDetailsPack(DetailsPack $detailsPack): self
    {
        if ($this->detailsPacks->removeElement($detailsPack)) {
            // set the owning side to null (unless already changed)
            if ($detailsPack->getPack() === $this) {
                $detailsPack->setPack(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setPack($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getPack() === $this) {
                $annonce->setPack(null);
            }
        }

        return $this;
    }

    public function getLabel(): string
    {
        return sprintf('%s Fcfa  %s  %s Jours', $this->getPrice(), $this->getPriceByDay(), $this->getDays());
    }

    public function getImage(): ?FilePicture
    {
        return $this->image;
    }

    public function setImage(FilePicture $image): self
    {
        $this->image = $image;

        return $this;
    }
}
