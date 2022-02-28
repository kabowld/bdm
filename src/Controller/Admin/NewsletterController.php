<?php

namespace App\Controller\Admin;

use App\Entity\Suscriber;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NewsletterController  extends AbstractController
{
    /**
     * @Route("/send-newsletter", name="send_newsletter_bdmk", methods={"POST"}, options={"expose"=true})
     */
    public function send(Request $request, ValidatorInterface $validator)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('home_bdmk');
        }

        $email = $request->request->get('email');
        $suscriber = new Suscriber($request->getClientIp());
        $suscriber->setEmail($email);

        if ($validator->validate($suscriber)->count() > 0) {
            return new JsonResponse(['message' => 'Erreur lors de l\'inscription', 'status' => 'fail'], 400);
        }

        // doctrine
        // email

        return new JsonResponse(['message' => 'Votre inscription a bien été effectuée.', 'status' => 'success']);
    }
}
