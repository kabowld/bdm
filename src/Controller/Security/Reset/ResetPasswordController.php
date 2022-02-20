<?php
declare(strict_types=1);

namespace App\Controller\Security\Reset;


use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ResetPasswordController
 *
 * @Route("/admin")
 */
class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/reset-password", name="reset_password_bdmk", methods={"GET", "POST"})
     *
     * @param Request                     $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @param UserManager                 $userManager
     * @return RedirectResponse|Response
     */
    public function resetPassword(Request $request, UserPasswordHasherInterface $passwordHasher, UserManager $userManager): Response
    {
        $resetPassword = new ResetPassword();
        $form = $this->createForm(ResetPasswordType::class, $resetPassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$passwordHasher->isPasswordValid($this->getUser(), $form->get('oldPassword')->getData())) {
                return $this->render(
                    'Security/Reset/password.html.twig',
                    ['error' => 'Votre mot de passe actuel n\'est pas le bon !', 'form' => $form->createView()]
                );
            }

            /** @var User $user */
            $user = $this->getUser();
            $userManager->updateAdminPassword(
                $user,
                $passwordHasher,
                ['user' => $this->getUser(), 'resetPassword' => $resetPassword->getPassword()]
            );

            $this->addFlash('success', 'Votre mot de passe a été modifié avec succès !');

            return $this->redirectToRoute('admin_dashboard_bdmk');
        }

        return $this->render('Security/Reset/password.html.twig', ['form' => $form->createView()]);
    }
}
