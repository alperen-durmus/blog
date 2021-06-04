<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('owner'),
            TextEditorField::new('content'),
            AssociationField::new("blog", "blog"),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {

        $replyAction = Action::new("reply", "Reply",  "fas fa-reply")
            ->linkToCrudAction("reply");
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_INDEX, $replyAction);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return  $this->getDoctrine()->getManager()->getRepository(Comment::class)->findSelfComments($this->getUser());
    }


    public function reply(AdminContext $context)
    {
        $request = $context->getRequest();

        $parent =  $context->getEntity()->getInstance();

        if($request->getMethod() == "POST") {
            $em = $this->getDoctrine()->getManager();

            $content = $request->request->get("form")['content'];
            $comment = new Comment();
            $comment->setBlog($parent->getBlog());
            $comment->setOwner($this->getUser()->getUsername());
            $comment->setContent($content);
            $comment->setParent($parent);

            $em->persist($comment);
            $em->flush();

        }

        $comment = new Comment();

        $form = $this->createFormBuilder($comment)
            ->add("content", TextareaType::class,[
                "label" => "Answer"
            ])
            ->add("reply", SubmitType::class)
            ->getForm();

        return $this->render("bundles/EasyAdminBundle/replyForm.html.twig", [
            "form" => $form->createView(),
            "parent" => $parent
        ]);
    }

}
