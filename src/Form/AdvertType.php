<?php

namespace App\Form;

use App\Entity\Advert;
use App\Entity\Category;
use App\Tools\Constant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required'=> true,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'required'=> true,
                'attr' => [
                    'class' => 'textarea',
                    'rows' => '6'
                ]
            ])
            ->add('author', TextType::class, [
                'label' => 'Auteur',
                'required'=> true,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required'=> true,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'required'=> true,
                'attr' => [
                    'class' => 'input'
                ],
                'invalid_message' => 'Le prix doit-être compris entre %min%€ et %max%€',
                'invalid_message_parameters' => ['%min%' => 1, '%max%' => 1000000.00],
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'button is-primary is-rounded'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
