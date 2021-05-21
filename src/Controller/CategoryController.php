<?php

namespace App\Controller;

use App\Entity\BlogCategory;
use App\Repository\BlogCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    public function getCategories(): Response
    {
        $categories = $this->getDoctrine()->getRepository(BlogCategory::class)->findAll();

        return $this->render(
            'category/index.html.twig',
            ['categories' => $categories]
        );
    }
}
