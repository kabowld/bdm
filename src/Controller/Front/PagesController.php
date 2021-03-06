<?php
declare(strict_types=1);

namespace App\Controller\Front;


use App\Entity\AnnonceSearch;
use App\Entity\Contact;
use App\Form\AnnonceSearchType;
use App\Form\ContactType;
use App\Repository\AnnonceRepository;
use App\Repository\CityRepository;
use App\Repository\RubriqueRepository;
use App\Service\SendMail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{

    /**
     * Home Action Controller
     *
     * @Route("/", name="home_bdmk", methods={"GET"})
     *
     * @param Request            $request
     * @param CityRepository     $cityRepository
     * @param RubriqueRepository $rubriqueRepository
     *
     * @return Response
     */
    public function index(Request $request, CityRepository $cityRepository, RubriqueRepository $rubriqueRepository, AnnonceRepository $annonceRepository): Response
    {
        $search = new AnnonceSearch();
        $form = $this->createForm(AnnonceSearchType::class, $search);
        $form->handleRequest($request);

        return $this->render('Front/Pages/home.html.twig', [
            'form' => $form->createView(),
            'cities' => $cityRepository->getCitiesByOrderTitle(),
            'rubriques' => $rubriqueRepository->getAllRubriqueAndCategories(),
            'annonces' => $annonceRepository->getLastFiveAnnonces(5)
        ]);
    }

    /**
     * Contact Action Controller
     *
     * @Route("/contact", name="contact_bdmk", methods={"GET", "POST"})
     *
     * @param Request  $request
     * @param SendMail $sendMail
     *
     * @return Response
     */
    public function contact(Request $request, SendMail $sendMail): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sendMail->createEmail(
                'arnaud.azan@gmail.com',
                $contact->getSubject(),
                'Email/contact.html.twig',
                ['info' => $contact]
            );
            $this->addFlash(Contact::SUCCESS, Contact::MESSAGE_SUCCESS);

            return $this->redirectToRoute('contact_bdmk');
        }

        return $this->render('Front/Pages/contact.html.twig', ['form' => $form->createView()]);
    }


    /**
     * About Action Controller
     *
     * @Route("/a-propos-de-nous", name="about_bdmk", methods={"GET"})
     *
     * @return Response
     */
    public function about(): Response
    {
        return $this->render('Front/Pages/about.html.twig');
    }

    /**
     * Condition Action Controller
     *
     * @Route("/cgu", name="cgu_bdmk", methods={"GET"})
     *
     * @return Response
     */
    public function cgu(): Response
    {
        return $this->render('Front/Pages/cgu.html.twig');
    }

}
