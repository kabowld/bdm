<?php

namespace App\Entity;

use App\Validator as BdmAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="Il existe déjà un compte avec cette adresse email!")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Serializable
{
    const ROLES = [
        'user'        => 'ROLE_USER',
        'pro'         => 'ROLE_PRO',
        'admin'       => 'ROLE_ADMIN',
        'super_admin' => 'ROLE_SUPER_ADMIN'
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Assert\Length(max=180, maxMessage="L'adresse mail excède le  nombre de caractère !")
     * @Assert\NotBlank(message="L'adresse doit être saisi pour créer le compte !")
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @Assert\All({
     *     @Assert\Length(max=50, maxMessage="Le rôle doit comporter {{ limit }} caractères maximum !"),
     *     @Assert\Regex(pattern="/^ROLE_/", message="Syntaxe incorrecte pour le rôle !")
     * })
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @Assert\NotBlank(message="Le mot de passe ne doit pas être vide !")
     * @BdmAssert\PasswordRules()
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $enabled;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $lastLogin;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private Groupe $groupe;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isVerified;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $resetToken;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $resetAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $confirmatoken = null;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $confirmationAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $societyName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $adresse;

    /**
     * @Assert\Length(max=15, maxMessage="Le numéro de téléphone n'est pas correcte")
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private ?string $tel;

    /**
     * @Assert\Length(max=15, maxMessage="Le titre de civilité n'est pas correcte")
     * @Assert\Choice({"monsieur", "madame", "mademoiselle"})
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private ?string $civility;

    /**
     * @ORM\ManyToOne(targetEntity=Rubrique::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Rubrique $rubrique;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?City $city;

    /**
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="owner", orphanRemoval=true)
     */
    private $annonces;

    /**
     * @Assert\Image(
     *     minWidth = 60,
     *     maxWidth = 800,
     *     minHeight = 60,
     *     maxHeight = 800,
     *     maxSize = "100M",
     *     maxSizeMessage = "La taille maximum de l'image doit être de {{ size }} {{ suffix }}",
     *     maxHeightMessage = "La hauteur maximum de l'image doit être de {{ max_width }}px",
     *     maxWidthMessage = "La largeur maximum de l'image doit être de {{ max_width }}px",
     *     minHeightMessage = "La hauteur maximum de l'image doit être de {{ min_height }}px",
     *     minWidthMessage = "La largeur maximum de l'image doit être de {{ min_width }}px",
     *     mimeTypes = {"image/png", "image/jpeg"},
     *     mimeTypesMessage = "Téléverser des images de type jpeg ou png !"
     * )
     */
    private $avatarFile = null;

    /**
     * @ORM\OneToOne(targetEntity=FilePicture::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $avatar = null;

    /**
     * @ORM\ManyToMany(targetEntity=Annonce::class, inversedBy="usersFavoris")
     * @ORM\JoinTable(name="user_favoris")
     */
    private $favoris;

    public function __construct()
    {
        $this->enabled = false;
        $this->createdAt = new \DateTimeImmutable();
        $this->isVerified = false;
        $this->annonces = new ArrayCollection();
        $this->favoris = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @param $role
     *
     * @return $this
     */
    public function addRole($role): self
    {
        $role = strtoupper($role);

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @param string $role
     *
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
    public function removeRole(string $role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return bool|null
     */
    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     *
     * @return $this
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeImmutable
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeImmutable $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

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

    /**
     * @param \DateTimeImmutable|null $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Groupe|null
     */
    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    /**
     * @param Groupe $groupe
     *
     * @return $this
     */
    public function setGroupe(Groupe $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getResetAt(): ?\DateTimeImmutable
    {
        return $this->resetAt;
    }

    public function setResetAt(?\DateTimeImmutable $resetAt): self
    {
        $this->resetAt = $resetAt;

        return $this;
    }

    public function getConfirmatoken(): ?string
    {
        return $this->confirmatoken;
    }

    public function setConfirmatoken(?string $confirmatoken): self
    {
        $this->confirmatoken = $confirmatoken;

        return $this;
    }

    public function getConfirmationAt(): ?\DateTimeImmutable
    {
        return $this->confirmationAt;
    }

    public function setConfirmationAt(?\DateTimeImmutable $confirmationAt): self
    {
        $this->confirmationAt = $confirmationAt;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getSocietyName(): ?string
    {
        return $this->societyName;
    }

    public function setSocietyName(?string $societyName): self
    {
        $this->societyName = $societyName;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getCivility(): ?string
    {
        return $this->civility;
    }

    public function setCivility(?string $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    public function getRubrique(): ?Rubrique
    {
        return $this->rubrique;
    }

    public function setRubrique(?Rubrique $rubrique): self
    {
        $this->rubrique = $rubrique;

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

    public static function listCivility(): array
    {
        return [
            'Monsieur' => 'monsieur',
            'Madame' => 'madame',
            'Mademoiselle' => 'mademoiselle',
        ];

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
            $annonce->setOwner($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getOwner() === $this) {
                $annonce->setOwner(null);
            }
        }

        return $this;
    }

    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    /**
     * @param UploadedFile $file
     *
     * @return self
     */
    public function setAvatarFile($file): self
    {
        $filePicture = new FilePicture();
        $filePicture->setFile($file);
        $this->setAvatar($filePicture);

        $this->avatarFile = $file;

        return $this;
    }

    public function getAvatar(): ?FilePicture
    {
        return $this->avatar;
    }

    public function setAvatar(?FilePicture $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    /**
     * @return Collection<int, Annonce>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Annonce $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
        }

        return $this;
    }

    public function removeFavori(Annonce $favori): self
    {
        $this->favoris->removeElement($favori);

        return $this;
    }
}
