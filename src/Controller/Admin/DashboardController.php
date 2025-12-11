<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Request;
use App\Entity\House;
use App\Entity\User;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->redirectToRoute('admin_user_index');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Feip Backend Labs');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Requests', 'fa fa-envelope', Request::class)->setController(RequestCrudController::class);
        yield MenuItem::linkToCrud('Houses', 'fa fa-envelope', House::class)->setController(HouseCrudController::class);
        yield MenuItem::linkToCrud('Users', 'fa fa-envelope', User::class)->setController(UserCrudController::class);
    }
}
