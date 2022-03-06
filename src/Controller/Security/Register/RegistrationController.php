<?php
declare(strict_types=1);

namespace App\Controller\Security\Register;


use App\Entity\User;
use App\Exception\UserManagerException;
use App\Form\RegistrationParticular;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{

    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * Registration particular user
     *
     * @Route("/creation/compte", name="registration_bdmk", methods={"GET", "POST"})
     *
     * @param Request $request
     * @throws UserManagerException
     *
     * @return Response
     */
    public function registerParticular(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute(UserManager::ADMIN_DASHBOARD_PATH);
        }

        $user = new User();
        $form = $this->createForm(RegistrationParticular::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userManager->registration($user, User::ROLES['user']);

            return $this->redirectToRoute('congratulation_bdmk', ['token' => $user->getConfirmatoken()]);
        }

        return $this->render('Security/Register/particular.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Registration particular user
     *
     * @Route("/creation/compte/pro", name="registration_pro_bdmk", methods={"GET", "POST"})
     *
     * @param Request $request
     * @throws UserManagerException
     *
     * @return Response
     */
    public function registerPro(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute(UserManager::ADMIN_DASHBOARD_PATH);
        }

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userManager->registration($user, User::ROLES['pro']);

            return $this->redirectToRoute('congratulation_bdmk', ['token' => $user->getConfirmatoken()]);
        }

        return $this->render('Security/Register/pro.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Congratulation after registration success
     *
     * @Route("/congratulation/{token}", name="congratulation_bdmk", methods={"GET"})
     *
     * @param string         $token
     * @param UserRepository $userRepository
     *
     * @return Response
     */
    public function congratulation(string $token, UserRepository $userRepository): Response
    {
        if (!$user = $userRepository->findOneBy(['confirmatoken' => $token])) {
            throw $this->createNotFoundException(UserManager::ERROR_MESS_NOT_FOUND);
        }

        return $this->render('Security/congratulation.html.twig', ['email' => $user->getEmail()]);
    }


}
