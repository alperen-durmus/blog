<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Entity\BlogCategory;
use App\Entity\Tag;
use App\Entity\User;
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
            ->setTitle('Admin')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Blog', 'fas fa-align-center', Blog::class),
            MenuItem::linkToCrud('Category', 'fas fa-list', BlogCategory::class),
            MenuItem::subMenu('Tag', 'fas fa-tags')->setSubItems([
                MenuItem::linkToCrud('List', 'fa fa-list', Tag::class),
                MenuItem::linkToCrud('New', 'fa fa-plus', Tag::class)->setAction('new'),
            ]),
        ];
    }
    
}
