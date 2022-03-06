<?php

namespace App\Manager;

use App\Core\GenerateToken;
use App\Entity\Suscriber;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NewsletterManager extends Manager
{
    public function subscription(ValidatorInterface $validator, string $email, string $addressIp)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(
                ['message' => 'Erreur lors de l\'inscription', 'status' => 'fail'],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }

        $this->validatorSubscriber($validator, $email,$addressIp);

        return new JsonResponse(
            ['message' => 'Votre inscription a bien été effectuée.', 'status' => 'success'],
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    public function confirmSubscription(Suscriber $suscriber)
    {
        $suscriber
            ->setSuscribedAt(new \DateTimeImmutable())
            ->setEnable(true)
            ->setConfirmToken(GenerateToken::getGenerateConfirmationToken())
        ;
    }

    private function validatorSubscriber(ValidatorInterface $validator, string $email, string $addressIp)
    {
        $suscriber = new Suscriber($addressIp);
        $suscriber
            ->setEmail($email)
            ->setConfirmToken(GenerateToken::getGenerateConfirmationToken())
        ;
        if ($validator->validate($suscriber)->count() === 0) {
            $this->em->persist($suscriber);
            $this->em->flush();

            $this->email->createEmail(
                $suscriber->getEmail(),
                'Confirmation d\'inscription Bandama Market Newsletter',
                'Email/susbcribe_email.html.twig', [
                    'localUrl' => $this->localUrl,
                    'url' => sprintf('%s/confirm/newsletter/%s', $this->localUrl, $suscriber->getConfirmToken())
                ]
            );
        }
    }
}
