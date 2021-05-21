<?php

namespace App\Controller;

use App\Repository\BlogCategoryRepository;
use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog")
     */
    public function index(BlogRepository $blogRepository): Response
    {
        $blogs = $blogRepository->findBy(["status" => 1], ['created_at' => 'DESC']);

        return $this->render('blog/index.html.twig', [
            'blogs' => $blogs,
            'title' => "All Posts"
        ]);
    }

    /**
     * @Route("/post/{id}", name="blog_detail")
     */
    public function detail($id, BlogRepository $blogRepository): Response
    {
        $blog = $blogRepository->find($id);

        return $this->render('blog/detail.html.twig', [
            'blog' => $blog,
            'title' => $blog->getTitle(),
        ]);
    }

    /**
     * @Route("/posts/category/{category_id}", name="category")
     */
    public function category($category_id, BlogCategoryRepository $blogCategoryRepository): Response {

        $categories = $blogCategoryRepository->find($category_id);
        return $this->render('blog/index.html.twig', [
            'blogs' => $categories->getBlogs(),
            'title' => $categories->getName(),
        ]);
    }

    /**
     * @Route("/blog/search", name="search")
     */
    public function search(BlogRepository $blogRepository, Request $request): Response {
        $searchKey = $request->request->get("searchKey");
        $blog = $blogRepository->findByTitleOrContentField($searchKey);

        return $this->render('blog/index.html.twig', [
            'blogs' => $blog,
            'title' => $searchKey,
        ]);
    }
}
