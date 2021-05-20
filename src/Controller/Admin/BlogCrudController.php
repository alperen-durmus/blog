<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BlogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Blog::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $blog = new Blog();
        $blog->setAuthor($this->getUser());
        return $blog;
    }
   
    public function configureFields(string $pageName): iterable
    {
        $tag = $this->getDoctrine()
            ->getRepository(Tag::class)
            ->findAll();


        return [
            TextField::new('title'),
            TextEditorField::new('content'),
            BooleanField::new('status'),
            DateTimeField::new('created_at'),
            DateTimeField::new('updated_at'),
            AssociationField::new('categories'),
            AssociationField::new('tags'),
        ];


    }
}
