<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Nom : '])
            ->add('description', TextType::class, ['label' => 'Description : '])
            ->add('author', TextType::class, ['label' => 'Auteur : '])
            ->add('category', EntityType::class, [
                'class'=>Category::class,
                'choice_label'=>'name',
            ])
//            ->add('add', SubmitType::class, ['label' => 'Envoyer'])
//            ->add('isPublished')
//            ->add('dateCreated')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
