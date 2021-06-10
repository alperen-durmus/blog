<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Entity\BlogCategory;
use App\Entity\Comment;
use App\Entity\Log;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\BlogCategoryRepository;
use App\Repository\BlogRepository;
use App\Repository\TagRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $blog = $this->getDoctrine()->getRepository(Blog::class)->count([]);
        $comment = $this->getDoctrine()->getRepository(Comment::class)->count([]);
        $author = $this->getDoctrine()->getRepository(User::class)->count([]);

        return $this->render('bundles/EasyAdminBundle/dashboard.html.twig', [
            "blog" => $blog,
            "comment" => $comment,
            "author" => $author,
        ]);
    }

    /**
     * @return Response
     * @Route("/admin/get-dashboard-data")
     */
    public function getDashboardData (BlogCategoryRepository $blogCategoryRepository, TagRepository $tagRepository, BlogRepository $blogRepository) {
        $category = $blogCategoryRepository->getBlogCountByCategory();
        $tag = $tagRepository->getBlogCountByTag();
        $user = $blogRepository->getBlogCountByUser();


        $data = [
            "category" => $category,
            "tag" => $tag,
            "user" => $user
        ];
        $response = new JsonResponse($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Admin')
            ->renderContentMaximized()
            ->disableUrlSignatures();
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addCssFile("css/admin.css")
            ->addJsFile("js/chart.js")
            ->addJsFile("js/scripts.js");
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
                yield MenuItem::section('Other');
                yield MenuItem::linkToCrud('Authors', 'fas fa-user-edit', User::class);
                yield MenuItem::linkToCrud('Log', 'fas fa-clipboard-list', Log::class);
            }
            yield MenuItem::linkToRoute("View", "fa fa-home", "blog");

    }
    
}
