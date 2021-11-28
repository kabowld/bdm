<?php
declare(strict_types=1);

namespace App\Controller\Security;


use App\Entity\Groupe;
use App\Entity\User;
use App\Form\RegistrationParticular;
use App\Form\RegistrationType;
use App\Service\HandlingUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    const LENGTH_CONFIRMATION_TOKEN = 80;
    private $dispatcher;
    private $handlingUser;
    private $em;

    public function __construct(EventDispatcherInterface $eventDispatcher, EntityManagerInterface $em, HandlingUser $handlingUser)
    {
        $this->dispatcher = $eventDispatcher;
        $this->em = $em;
        $this->handlingUser = $handlingUser;
    }

    /**
     * @Route("/choix/compte", name="choice_registration_bdmk", methods={"GET"})
     */
    public function choiceRegistration(): Response
    {
        return $this->render('Security/choice_registration.html.twig');
    }

    /**
     * Registration particular user
     *
     * @Route("/creation/compte", name="registration_bdmk", methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function registerParticular(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationParticular::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupe = $this->em->getRepository(Groupe::class)->findOneBy(['role' => 'ROLE_USER']);
            $user->setConfirmatoken($this->handlingUser->setConfirmationToken(self::LENGTH_CONFIRMATION_TOKEN));
            $user->setGroupe($groupe);
            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('congratulation_bdmk', ['token' => $user->getConfirmatoken()]);
        }

        return $this->render('Security/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Registration particular user
     *
     * @Route("/creation/compte/pro", name="registration_pro_bdmk", methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function registerPro(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $groupe = $this->em->getRepository(Groupe::class)->findOneBy(['role' => 'ROLE_PRO']);
            $user->setConfirmatoken($this->handlingUser->setConfirmationToken(self::LENGTH_CONFIRMATION_TOKEN));
            $user->setGroupe($groupe);
            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('congratulation_success_bdmk', ['token' => $user->getConfirmatoken()]);
        }

        return $this->render('Security/registerPro.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Congratulation account
     *
     * @Route("/congratulation/{token}", name="congratulation_bdmk", methods={"GET"})
     *
     * @param Request $request
     * @param string $token
     *
     * @return Response
     */
    public function congratulation(Request $request, string $token): Response
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['confirmatoken' => $token]);
        if (!$user) {
            throw $this->createNotFoundException('Error 404');
        }

        return $this->render('Security/congratulation.html.twig', ['email' => $user->getEmail()]);
    }


}
