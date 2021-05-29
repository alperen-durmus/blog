<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
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

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setUpdatedAt(new \DateTime());
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        if ($this->isGranted("ROLE_ADMIN")) {
                return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters); // TODO: Change the autogenerated stub
        }
        return  $this->getDoctrine()->getManager()->getRepository(Blog::class)->findSelfBlogs($this->getUser());
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextEditorField::new('content'),
            ImageField::new('image')->setUploadDir("public/image/blog/")->setBasePath("/image/blog/"),
            BooleanField::new('status'),
            AssociationField::new('author')->hideOnForm(),
            DateTimeField::new('created_at')->hideOnForm(),
            DateTimeField::new('updated_at')->hideOnForm(),
            AssociationField::new('comments')->hideOnForm(),
            AssociationField::new('categories'),
            AssociationField::new('tags'),
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
