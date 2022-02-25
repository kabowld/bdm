<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact {

    const SUCCESS = 'success';
    const MESSAGE_SUCCESS = 'Votre message a bien été envoyé, nous vous répondrons dans les plus brefs délais !';

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Le nom ne doit pas être vide !")
     */
    private string $name;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="L'adresse mail ne doit pas être vide !")
     * @Assert\Email(message="L'adresse email {{ value }} n'est pas valide !")
     */
    private string $email;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Le sujet ne doit pas être vide !")
     */
    private string $subject;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Le message ne doit pas être vide !")
     */
    private string $message;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return self
     */
    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return self
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

}
