<?php

namespace App\Controller\Security\Reset;

use App\Entity\RecoveryPassword;
use App\Exception\UserManagerException;
use App\Form\RecoveryPasswordType;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RecoveryPasswordController
 */
class RecoveryPasswordController extends AbstractController
{
    /**
     * @Route(
     *     "/rappel-mot-de-passe",
     *     name="recovery_password_bdmk",
     *     methods={"GET", "POST"},
     *     options ={"expose"=true}
     * )
     *
     * @param Request            $request
     * @param UserManager        $userManager
     * @param ValidatorInterface $validator
     *
     * @return Response
     */
    public function recoveryPassword(Request $request, UserManager $userManager, ValidatorInterface $validator): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('admin_dashboard_bdmk');
        }

        if ($request->isXmlHttpRequest()) {
            return $userManager->resetEmailPassword($validator, $request->request->get('email'));
        }

        return $this->render('Security/Reset/recovery_password.html.twig');
    }

    /**
     * @Route(
     *     "/reset-password-confirm/{token}",
     *     name="reset_password_confirm_bdmk",
     *     methods={"GET", "POST"}
     * )
     *
     * @param Request                     $request
     * @param string                      $token
     * @param UserPasswordHasherInterface $passwordHasher
     * @param UserManager                 $userManager
     *
     * @throws UserManagerException
     *
     * @return Response
     */
    public function resetPassword(Request $request, string $token, UserPasswordHasherInterface $passwordHasher, UserManager $userManager): Response
    {
        $user = $userManager->findUserByToken($token);

        if ($userManager->checkDateLinkExpiration($user)) {
            $userManager->resetTokenPassword($user, true);

            return $this->render('Security/Reset/link_expired.html.twig');
        }

        $recovery = new RecoveryPassword();
        $form = $this->createForm(RecoveryPasswordType::class, $recovery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->updatePassword($user, $passwordHasher, $form->get('password')->getData());

            return $this->redirectToRoute('login_bdmk');
        }

        return $this->render('Security/Reset/confirm_password.html.twig', ['form' => $form->createView()]);
    }
}
