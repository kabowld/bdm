<?php
declare(strict_types=1);

namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardController
 * @Route("/admin")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="admin_dashboard_bdmk", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('Admin/Pages/dashboard.html.twig');
    }
}
