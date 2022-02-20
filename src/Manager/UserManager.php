<?php
declare(strict_types=1);

namespace App\Manager;

use App\Core\GenerateToken;
use App\Entity\Groupe;
use App\Entity\ResetEmailPassword;
use App\Entity\User;
use App\Exception\UserManagerException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserManager extends Manager
{
    const FAIL_STATUS = 'fail';
    const WELCOME_PATH = 'Email/welcome.html.twig';
    const SUCCESS_STATUS = 'success';
    const MESS_LINK_EXPIRED = 'Désolé le lien d\'activation a expiré';
    const WELCOME_SUBJECT_MAIL = 'Bienvenue chez Bandama Market !';
    const ADMIN_DASHBOARD_PATH = 'admin_dashboard_bdmk';
    const ERROR_MESS_NOT_FOUND = 'La page que vous recherchez est introuvable !';
    const MESS_SUCCESS_ACTIVATION = 'Bravo, votre compte a été activé avec succès !';
    const DURATION_TOKEN_VALIDATE = 3600;
    const CONFIRM_RESET_EMAIL_SUBJECT = 'Mot de passe modifié avec succès !';
    const CONFIRM_RESET_EMAIL_PATH = 'Email/confirm_reset.html.twig';

    /**
     * Registration user account
     * If role doesn't exist exception will throw
     *
     * @param User   $user
     * @param string $role
     *
     * @throws UserManagerException
     *
     * @return void
     */
    public function registration(User $user, string $role): void
    {
        $groupe = $this->getEntityRepository(Groupe::class)->findOneBy(['role' => $role]);

        if (!$groupe) {
            throw new UserManagerException(UserManagerException::ROLE_EXCEPTION_MESSAGE);
        }

        $user->setConfirmatoken(GenerateToken::getGenerateConfirmationToken());
        $user->setGroupe($groupe);
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     *
     * @return string[]
     */
    public function validateAccount(User $user): array
    {
        if (!$this->checkDateLinkExpiration($user)) {
            $user->setConfirmatoken(null);
            $this->em->flush();

            return ['status' => self::FAIL_STATUS, 'message' => self::MESS_LINK_EXPIRED];
        }

        //Update user
        $this->confirmUserRegistration($user);

        // Send an email to welcome
        $this->email->createEmail(
            $user->getEmail(),
            self::WELCOME_SUBJECT_MAIL,
            self::WELCOME_PATH,
            ['url' => sprintf('%s/admin/annonce/ajout', $this->localUrl)]
        );

        return ['status' => self::SUCCESS_STATUS, 'message' => self::MESS_SUCCESS_ACTIVATION];
    }

    /**
     * @param ValidatorInterface $validator
     * @param string|null        $emailInput
     *
     * @return JsonResponse
     */
    public function resetEmailPassword(ValidatorInterface $validator, ?string $emailInput): JsonResponse
    {
        if ($this->validateEmailPassword($validator, $emailInput)->count() > 0) {
            return $this->createJsonResponseResetPassword(sprintf(ResetEmailPassword::INVALID_EMAIL, $emailInput), true);
        }

        $user = $this->getEntityRepository(User::class)->findOneBy(['email' => $emailInput]);
        if (!$user) {
            return $this->createJsonResponseResetPassword(ResetEmailPassword::NO_EMAIL_ACCOUNT, true);
        }

        $this->resetTokenPassword($user);
        // Envoie d'email
        $this->email->createEmail(
            $user->getEmail(),
            ResetEmailPassword::RECOVERY_SUBJECT_EMAIL,
            'Email/recovery_password.html.twig',
            ['url' => sprintf('%s/reset-password-confirm/%s', $this->localUrl, $user->getResetToken())]
        );

        return $this->createJsonResponseResetPassword(ResetEmailPassword::SUCCESS_EMAIL);
    }

    /**
     * Check expiration link
     *
     * @param User $user
     *
     * @return bool
     */
    public function checkDateLinkExpiration(User $user): bool
    {
        $result = (new \DateTimeImmutable())->getTimestamp() - $user->getCreatedAt()->getTimestamp();
        if ($result > self::DURATION_TOKEN_VALIDATE) {
            return false;
        }

        return true;
    }

    /**
     * @param string $token
     *
     * @throws UserManagerException
     *
     * @return User
     */
    public function findUserByToken(string $token): User
    {
        $user = $this->getEntityRepository(User::class)->findOneBy(['resetToken' => $token]);
        if (!$user) {
            throw new UserManagerException(self::ERROR_MESS_NOT_FOUND);
        }

        return $user;
    }

    /**
     * @param User $user
     * @param bool $reset
     *
     * @return void
     */
    public function resetTokenPassword(User $user, bool $reset = false): void
    {
        if ($reset) {
            $user
                ->setResetAt(null)
                ->setResetToken(null)
            ;
            $this->em->flush();

            return;
        }

        $user
            ->setResetToken(GenerateToken::getGenerateConfirmationToken())
            ->setResetAt(new \DateTimeImmutable())
        ;
        $this->em->flush();
    }

    /**
     * @param User                        $user
     * @param UserPasswordHasherInterface $passwordHasher
     * @param string                      $password
     *
     * @return void
     */
    public function updatePassword(User $user, UserPasswordHasherInterface $passwordHasher, string $password): void
    {
        $user->setPassword($passwordHasher->hashPassword($user, $password));
        $this->resetTokenPassword($user, true);

        // Send an email to welcome
        $this->email->createEmail(
            $user->getEmail(),
            self::CONFIRM_RESET_EMAIL_SUBJECT,
            self::CONFIRM_RESET_EMAIL_PATH
        );
    }

    /**
     * @param ValidatorInterface $validator
     * @param string             $email
     *
     * @return ConstraintViolationListInterface
     */
    private function validateEmailPassword(ValidatorInterface $validator, string $email): ConstraintViolationListInterface
    {
        $emailReset = new ResetEmailPassword();
        $emailReset->setEmail($email);

        return $validator->validate($emailReset);
    }

    /**
     * Update User after confirm registration
     *
     * @param User $user
     *
     * @return void
     */
    private function confirmUserRegistration(User $user): void
    {
        $user
            ->setIsVerified(true)
            ->setConfirmatoken(null)
            ->setConfirmationAt(new \DateTimeImmutable())
            ->setEnabled(true)
        ;

        $this->em->flush();
    }

    /**
     * @param string $message
     * @param bool   $fail
     *
     * @return JsonResponse
     */
    private function createJsonResponseResetPassword(string $message, bool $fail = false): JsonResponse
    {
        if ($fail) {
            return new JsonResponse(
                ['status' => 'fail', 'message' => $message],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }

        return new JsonResponse(
            ['status' => 'success', 'message' => $message],
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

}
