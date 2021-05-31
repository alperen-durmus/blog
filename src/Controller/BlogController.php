<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\BlogCategory;
use App\Entity\Comment;
use App\Entity\Subscribers;
use App\Repository\BlogRepository;
use App\Repository\CommentRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog")
     */
    public function index(BlogRepository $blogRepository, LoggerInterface $logger, Request $request): Response
    {

        $blogs = $blogRepository->findBy(["status" => 1], ['created_at' => 'DESC']);

        return $this->render('blog/index.html.twig', [
            'blogs' => $blogs,
            'title' => "All Posts",

        ]);
    }

    /**
     * @Route("/post/{id}", name="blog_detail")
     */
    public function detail($id, Blog $blog, CommentRepository $commentRepository, Request $request, ValidatorInterface $validator): Response
    {
        $comments = $commentRepository->findBy(["blog" => $blog]);
        $nested_comments = $commentRepository->getNestedComments();

        $response_data = [
            'blog' => $blog,
            'title' => $blog->getTitle(),
            'comments' => $comments,
            'nested_comments' => $nested_comments,
            'errors' => false,
        ];

        if ($_POST) {
            $entityManager = $this->getDoctrine()->getManager();

            $owner = $request->request->get("owner");
            $content = $request->request->get("content");

            $comment = new Comment();
            $comment->setOwner($owner);
            $comment->setContent($content);
            $comment->setBlog($blog);

            $errors = $validator->validate($comment);
            if (count($errors) > 0) {
                $response_data['errors'] = $errors;
            } else {
                $entityManager->persist($comment);
                $entityManager->flush();
                return $this->redirectToRoute("blog_detail", ["id" => $id]);
            }
        }

        return $this->render('blog/detail.html.twig', $response_data);
    }

    /**
     * @Route("/posts/category/{id}", name="category")
     */
    public function category(BlogCategory $category): Response
    {
        return $this->render('blog/index.html.twig', [
            'blogs' => $category->getBlogs()->filter(function (Blog $blog) {
                return (bool)$blog->getStatus();
            }),
            'title' => $category->getName(),
        ]);
    }

    /**
     * @Route("/blog/search", name="search", methods={"POST"})
     */
    public function search(BlogRepository $blogRepository, Request $request): Response
    {
        $searchKey = $request->request->get("searchKey");
        $blog = $blogRepository->findByTitleOrContentField($searchKey);

        return $this->render('blog/index.html.twig', [
            'blogs' => $blog,
            'title' => $searchKey,
        ]);
    }


}
