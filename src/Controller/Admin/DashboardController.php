<?php

namespace App\Controller\Admin;

use App\Entity\Agence;
use App\Entity\Client;
use App\Entity\Compte;
use App\Entity\Role;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SenkooparBack');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-list', Utilisateur::class);
        yield MenuItem::linkToCrud('Role', 'fas fa-list', Role::class);
        yield MenuItem::linkToCrud('Agence', 'fas fa-list', Agence::class);
        yield MenuItem::linkToCrud('Compte', 'fas fa-list', Compte::class);
        yield MenuItem::linkToCrud('Client', 'fas fa-list', Client::class);
    }
}
