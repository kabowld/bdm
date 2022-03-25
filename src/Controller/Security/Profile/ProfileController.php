<?php


namespace App\Controller\Security\Profile;

use App\Entity\User;
use App\Form\ProfilParticularType;
use App\Form\ProfilProType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ProfileController
 *
 * @Route("/admin")
 */
class ProfileController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;
    private $em;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em)
    {
        $this->passwordHasher = $passwordHasher;
        $this->em = $em;
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @Route("/edit-profile", name="edit_profile_admin_bdmk", methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function editProfile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfilParticularType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           if (!$this->passwordHasher->isPasswordValid($user, $form->get('passwordVerify')->getData())) {
               return $this->render(
                   'Security/Profile/particular.html.twig',
                   ['error' => 'Ce mot de passe n\'est pas le bon !', 'form' => $form->createView(), 'profil' => $this->getUser()]
               );
           }

            if (empty($form->get('name')->getData())) {
                return $this->render(
                    'Security/Profile/particular.html.twig',
                    ['error' => 'Saisir votre nom s\'il vous plaît', 'form' => $form->createView(), 'profil' => $this->getUser()]
                );
            }

            if (empty($form->get('firstname')->getData())) {
                return $this->render(
                    'Security/Profile/particular.html.twig',
                    ['error' => 'Saisir votre prénom s\'il vous plaît', 'form' => $form->createView(), 'profil' => $this->getUser()]
                );
            }

            if ($user->getFisrtLogin()) {
                $user->setFisrtLogin(false);
            };

            /** @var User $user */
            $user->setUpdatedAt(new \DateTimeImmutable());
            $this->em->flush();

            $this->addFlash('success', 'Votre profil a été modifié avec succès !');

            return $this->redirectToRoute('admin_dashboard_bdmk');
        }

        return $this->render('Security/Profile/particular.html.twig', [
            'form' => $form->createView(),
            'profil' => $this->getUser()
        ]);
    }

    /**
     * @IsGranted("ROLE_PRO")
     *
     * @Route("/edit-profile/pro", name="edit_profile_pro_admin_bdmk", methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function editProfilePro(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfilProType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->passwordHasher->isPasswordValid($user, $form->get('passwordVerify')->getData())) {
                return $this->render(
                    'Security/Profile/pro.html.twig',
                    ['error' => 'Ce mot de passe n\'est pas le bon !', 'form' => $form->createView(), 'profil' => $this->getUser()]
                );

            }
            if (empty($form->get('name')->getData())) {
                return $this->render(
                    'Security/Profile/pro.html.twig',
                    ['error' => 'Saisir votre nom s\'il vous plaît', 'form' => $form->createView(), 'profil' => $this->getUser()]
                );
            }

            if (empty($form->get('firstname')->getData())) {
                return $this->render(
                    'Security/Profile/pro.html.twig',
                    ['error' => 'Saisir votre prénom s\'il vous plaît', 'form' => $form->createView(), 'profil' => $this->getUser()]
                );
            }

            if ($user->getFisrtLogin()) {
                $user->setFisrtLogin(false);
            };
            /** @var User $user */
            $user->setUpdatedAt(new \DateTimeImmutable());
            $this->em->flush();

            $this->addFlash('success', 'Votre profil a été modifié avec succès !');

            return $this->redirectToRoute('admin_dashboard_bdmk');
        }

        return $this->render('Security/Profile/pro.html.twig', ['form' => $form->createView(), 'profil' => $this->getUser()]);
    }
}
