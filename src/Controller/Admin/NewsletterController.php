<?php

namespace App\Controller\Admin;

use App\Entity\Suscriber;
use App\Manager\NewsletterManager;
use App\Manager\UserManager;
use App\Repository\SuscriberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NewsletterController  extends AbstractController
{
    /**
     * @Route("/send-newsletter", name="send_newsletter_bdmk", methods={"POST"}, options={"expose"=true})
     *
     * @param Request            $request
     * @param ValidatorInterface $validator
     * @param NewsletterManager  $newsletterManager
     *
     * @return JsonResponse|RedirectResponse
     */
    public function send(Request $request, ValidatorInterface $validator, NewsletterManager $newsletterManager): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('home_bdmk');
        }

        return $newsletterManager->subscription($validator, $request->request->get('email'), $request->getClientIp());
    }


    /**
     * Action to validate Account
     *
     * @Route("/confirm/newsletter/{token}", name="confirm_suscription_newsletter", methods={"GET"}, requirements={"token" = ".+"})
     *
     * @param string              $token
     * @param SuscriberRepository $suscriberRepository
     * @param NewsletterManager   $newsletterManager
     *
     * @return Response
     */
    public function validationUserAccount(string $token, SuscriberRepository $suscriberRepository, NewsletterManager $newsletterManager): Response
    {
        if (!$suscriber = $suscriberRepository->findOneBy(['confirmToken' => $token])) {
            throw $this->createNotFoundException(UserManager::ERROR_MESS_NOT_FOUND);
        }
        $newsletterManager->confirmSubscription($suscriber);

        return $this->render('Newsletter/confirm.html.twig');
    }
}
