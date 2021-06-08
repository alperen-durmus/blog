<?php


namespace App\Form;


use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class searchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('search', TextType::class, [
            'required' => true,
            'constraints' => [new Length(['min' => 3])],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Search'
        ]);
    }
}