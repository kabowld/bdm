<?php
declare(strict_types=1);

namespace App\Controller\Security\Confirmation;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfirmationController extends AbstractController
{
    /**
     * @Route("/validation/compte/{token}", name="validation_account_bdmk", methods={"GET"})
     *
     * @param string $token
     *
     * @return Response
     */
    public function confirmationAccount(string $token): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['confirmatoken' => $token]);
        if (!$user) {
            throw $this->createNotFoundException('Error 404');
        }

        /** @var User $user */
        $today = new \DateTimeImmutable();
        $result = $today->getTimestamp() - $user->getCreatedAt()->getTimestamp();
        if ($result > 3600) {
            $user
                ->setConfirmatoken(null)
            ;
            $em->flush();

            return new Response('Le lien a expiré !');
        }
        /** @var User $user */
        $user
            ->setIsVerified(true)
            ->setConfirmatoken(null)
            ->setConfirmationAt(new \DateTimeImmutable())
            ->setEnabled(true)
        ;
        $em->flush();

        return $this->render('Security/confirmation.html.twig');
    }
}
