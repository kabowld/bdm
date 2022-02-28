<?php

namespace App\Entity;

use App\Repository\SuscriberRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SuscriberRepository::class)
 * @UniqueEntity(fields={"email"}, message="Il existe déjà un compte avec cette adresse email!")
 */
class Suscriber
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Assert\NotBlank(message="L'adresse doit être saisi pour s'abonner à la newsletter !")
     * @Assert\Email(message="L'adresse email n'est pas valide !")
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ipAddress;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $suscribedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $unsubscribedAt;

    public function __construct(string $ipAddress)
    {
        $this->createdAt = new \DateTime();
        $this->ipAddress = $ipAddress;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
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

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getSuscribedAt(): ?\DateTimeImmutable
    {
        return $this->suscribedAt;
    }

    public function setSuscribedAt(?\DateTimeImmutable $suscribedAt): self
    {
        $this->suscribedAt = $suscribedAt;

        return $this;
    }

    public function getUnsubscribedAt(): ?\DateTimeImmutable
    {
        return $this->unsubscribedAt;
    }

    public function setUnsubscribedAt(?\DateTimeImmutable $unsubscribedAt): self
    {
        $this->unsubscribedAt = $unsubscribedAt;

        return $this;
    }
}
