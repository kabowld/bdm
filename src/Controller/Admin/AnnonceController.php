<?php
declare(strict_types=1);

namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnonceController extends AbstractController
{
    public function liste()
    {
        return $this->render('Admin/Annonce/liste.html.twig', []);
    }
}
