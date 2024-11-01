<?php

namespace App\Controller\Admin;

use App\Entity\GitUser;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 *
 */
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
         return $this->redirect($adminUrlGenerator
             ->setController(GitUserCrudController::class)
             ->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GitLab User Control');
    }

    public function configureMenuItems(): iterable
    {
        $array = [
            MenuItem::linkToCrud('Users', 'fa fa-users', GitUser::class),
        ];

        foreach ($array as $item) {
            yield $item;
        }
    }
}
