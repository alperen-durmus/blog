<?php

namespace App\Controller;

use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
