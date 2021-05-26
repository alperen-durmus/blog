<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Entity\BlogCategory;
use App\Entity\Comment;
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
            ->renderContentMaximized()
            ->disableUrlSignatures();
    }

    public function configureMenuItems(): iterable
    {
            yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
            yield MenuItem::linkToCrud('Blog', 'fas fa-align-center', Blog::class);
            yield MenuItem::linkToCrud("Comments", "fas fa-comments", Comment::class);
            // ROLE_ADMIN
            if ($this->isGranted('ROLE_ADMIN')) {
                yield MenuItem::linkToCrud('Category', 'fas fa-list', BlogCategory::class);
                yield MenuItem::subMenu('Tag', 'fas fa-tags')->setSubItems([
                    MenuItem::linkToCrud('List', 'fa fa-list', Tag::class),
                    MenuItem::linkToCrud('New', 'fa fa-plus', Tag::class)->setAction('new'),
                ]);
                yield MenuItem::section('Other')->setPermission('ROLE_ADMIN');
                yield MenuItem::linkToCrud('Authors', 'fas fa-man', User::class)->setPermission('ROLE_ADMIN');
            }
    }
    
}
