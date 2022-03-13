<?php

namespace App\Entity;

use App\Manager\AnnonceFilePictureTrait;
use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as BdmAssert;

/**
 * @BdmAssert\StateAnnonceRules()
 * @BdmAssert\PriceAnnonceRules()
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 */
class Annonce
{

    use AnnonceFilePictureTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @Assert\Length(min=3, max=150, minMessage="Le titre doit comporter au moins {{ limit }} !", minMessage="Le titre doit comporter {{ limit }} au maximum !")
     * @Assert\NotBlank(message="Le titre ne doit pas être vide !")
     * @ORM\Column(type="string", length=255)
     */
    private ?string $title;

    /**
     * @Assert\NotBlank(message="Le choix du type d'une annonce doit être mentionné !")
     * @Assert\Choice({"offre", "demande"})
     * @ORM\Column(type="string", length=7)
     */
    private ?string $type;

    /**
     * @Assert\NotBlank(message="Veuillez saisir une description !")
     * @Assert\Length(min=5, minMessage="La description doit comporter au moins {{ limit }} !")
     * @ORM\Column(type="text")
     */
    private ?string $description;

    /**
     * @ORM\Column(type="float", nullable=true, scale=2)
     */
    private ?float $price = null;

    /**
     * @ORM\ManyToOne(targetEntity=State::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private ?State $state = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $postalCode;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $updatedAt;

    /**
     * @Assert\NotBlank(message="La ville ne doit pas être vide !")
     * @ORM\ManyToOne(targetEntity=City::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\Column(type="float", precision=10, nullable=true)
     */
    private $lat;

    /**
     * @ORM\Column(type="float", precision=10, nullable=true)
     */
    private $lng;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @Assert\Count(
     *      max = 8,
     *      maxMessage = "Vous pouvez saisir {{ limit }} photos au maximum",
     * )
     * @ORM\OneToMany(targetEntity=FilePicture::class, mappedBy="annonce", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $filePictures;

    /**
     * @ORM\ManyToOne(targetEntity=Pack::class, inversedBy="annonces")
     */
    private $pack;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->filePictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getFormatPrice(): string
    {
        return number_format($this->price, 0, ',', ' ');
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(?float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|FilePicture[]
     */
    public function getFilePictures(): Collection
    {
        return $this->filePictures;
    }

    public function getFilePicture(): ?FilePicture
    {
        if ($this->filePictures->isEmpty()) {
            return null;
        }

        return $this->filePictures->first();
    }

    public function addFilePicture(FilePicture $file): self
    {
        if (!$this->filePictures->contains($file)) {
            $this->filePictures[] = $file;
            $file->setAnnonce($this);
        }

        return $this;
    }

    public function removeFilePicture(FilePicture $filePicture): self
    {
        if ($this->filePictures->contains($filePicture)) {
            $this->filePictures->removeElement($filePicture);
            // set the owning side to null (unless already changed)
            if ($filePicture->getAnnonce() === $this) {
                $filePicture->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getPack(): ?Pack
    {
        return $this->pack;
    }

    public function setPack(?Pack $pack): self
    {
        $this->pack = $pack;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }



}
