<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FaqController extends AbstractController
{
    /**
     * @Route("/faq", name="faq_bdmk", methods={"GET"})
     */
    public function faqHome(): Response
    {
        return $this->render('Front/Faq/home.html.twig');
    }

    /**
     * @Route("/faq/article", name="faq_article_bdmk", methods={"GET"})
     */
    public function faqArticle(): Response
    {
        return $this->render('Front/Faq/article.html.twig');
    }
}
