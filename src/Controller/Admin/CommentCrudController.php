<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use Doctrine\DBAL\Types\TextType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
            AssociationField::new("blog", "blog")
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

    /**
     *
     */
    public function reply(AdminContext $context)
    {
        $comment =  $context->getEntity()->getInstance();

        return $this->render("bundles/EasyAdminBundle/replyForm.html.twig", [
           
        ]);
    }

}
