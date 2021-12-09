<?php
declare(strict_types=1);

namespace App\Controller\Security\Confirmation;


use App\Repository\UserRepository;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfirmationController extends AbstractController
{
    /**
     * Action to validate Account
     *
     * @Route("/validation/compte/{token}", name="validation_account_bdmk", methods={"GET"}, requirements={"token" = ".+"})
     *
     * @param string         $token
     * @param UserRepository $userRepository
     * @param UserManager    $userManager
     *
     * @return Response
     */
    public function validationUserAccount(string $token, UserRepository $userRepository, UserManager $userManager): Response
    {
        if (!$user = $userRepository->findOneBy(['confirmatoken' => $token])) {
            throw $this->createNotFoundException(UserManager::ERROR_MESS_NOT_FOUND);
        }
        $info = $userManager->validateAccount($user);

        return $this->render('Security/confirmation.html.twig', compact('info'));
    }
}
