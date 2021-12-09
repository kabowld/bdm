<?php


namespace App\Controller\Security\Reset;


use App\Core\Utils;
use App\Entity\RecoveryPassword;
use App\Entity\User;
use App\Form\RecoveryPasswordType;
use App\Service\UserManager;
use App\Service\SendMail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RecoveryPasswordController
 */
class RecoveryPasswordController extends AbstractController
{
    /**
     * @Route("/rappel-mot-de-passe", name="recovery_password_bdmk", methods={"GET", "POST"}, options ={"expose"=true})
     *
     * @param Request $request
     * @param UserManager $handlingUser
     *
     * @param SendMail $sendMail
     * @param Utils $utils
     * @return Response
     */
    public function recoveryPassword(Request $request, UserManager $handlingUser, SendMail $sendMail, Utils $utils): Response
    {
        $handlingUser->redirectUserIfLogged();
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneBy(['email' => $request->request->get('email')]);

            if (!$user) {
                return $this->json(
                    ['state' => 'fail', 'message' => 'Aucun compte ne correspond. Merci de vérifier votre adresse e-mail.'],
                    JsonResponse::HTTP_NOT_FOUND,
                    ['Content-Type' => 'application/json']
                );
            }

            /**
             * Reset password
             *
             * @var User $user
             */
            $user
                ->setResetToken($handlingUser->setConfirmationToken(100))
                ->setResetAt(new \DateTimeImmutable())
            ;

            $em->flush();

            // Envoie d'email
            $sendMail->validAccountUser(
                $user->getEmail(),
                'Récupération mot de passe',
                'Email/recovery_password.html.twig',
                ['url' => sprintf('%s/reset-password-confirm/%s', $utils->getUri(), $user->getResetToken())]
            );

            return $this->json(
                ['state' => 'success', 'message' => 'Un e-mail vient de vous être envoyé !'],
                JsonResponse::HTTP_OK,
                ['Content-Type' => 'application/json']
            );
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
     * @param Request $request
     * @param string $token
     *
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    public function resetPassword(Request $request, string $token, UserPasswordHasherInterface $passwordHasher): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['resetToken' => $token]);
        if (!$user) {
            throw $this->createNotFoundException('Error 404');
        }

        /** @var User $user */
        $today = new \DateTimeImmutable();
        $result = $today->getTimestamp() - $user->getResetAt()->getTimestamp();
        if ($result > 3600) {
            $user->setResetAt(null)->setResetToken(null);
            $em->flush();
            return new Response('Le lien a expiré !');
        }

        $recovery = new RecoveryPassword();
        $form = $this->createForm(RecoveryPasswordType::class, $recovery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
            $user->setResetAt(null)->setResetToken(null);
            $em->flush();

            return $this->redirectToRoute('login_bdmk');
        }

        return $this->render('Security/Reset/confirm_password.html.twig', ['form' => $form->createView()]);
    }
}
