<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class ResetEmailPassword
{
    const INVALID_EMAIL = 'L\'adresse mail "%s" n\'est pas valide !';
    const SUCCESS_EMAIL = 'Un e-mail vient de vous être envoyé !';
    const RECOVERY_SUBJECT_EMAIL = 'Réinitialisation de mot de passe';
    const NO_EMAIL_ACCOUNT = 'Aucun compte lié à cette adresse e-mail !';

    /**
     * @Assert\NotBlank(message="Veuillez saisir une adresse valide !")
     * @Assert\Email(message = "L'adresse mail {{ value }} n'est pas valide !")
     */
    private string $email;

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
     * @return void
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
}
