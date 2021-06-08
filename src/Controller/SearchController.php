<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search", methods={"POST"})
     */
    public function index(): Response
    {
        $form = $this->createForm(SearchType::class);

        return $this->render('search/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
