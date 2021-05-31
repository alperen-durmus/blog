<?php

namespace App\Controller;

use App\Entity\Subscribers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubscribeController extends AbstractController
{
    /**
     * @Route("/blog/subscribe", name="subscribe", methods={"POST"})
     */
    public function index(Request $request, ValidatorInterface $validator): Response
    {
        $subscriber = new Subscribers();

        $form = $this->createFormBuilder($subscriber)
            ->setAction($this->generateUrl("subscribe"))
            ->setMethod("POST")
            ->add("email", TextType::class)
            ->add("subscribe", SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($request->getMethod() === Request::METHOD_POST){
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $data = $form->getData();
                $em->persist($data);
                $em->flush();
                $this->addFlash("info", "You have subscribed the blog" );
            } else {
                $errors = $validator->validate($form)->get(0)->getMessage();
                $this->addFlash("subscribe_errors", $errors);
            }

            $refererUrl = $request->headers->get('referer');
            return $this->redirect($refererUrl);
        }

        return $this->render("subscribe/index.html.twig", [
            "form" => $form->createView(),
        ]);
    }
}
