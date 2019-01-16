<?php

namespace App\Form;

use App\Entity\Domain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DomainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('domain', TextareaType::class, [
                'label' => 'Domaine'
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre du site (méta)'
            ])
            ->add('description', TextType::class, [
                'label' => 'Description du site (méta)'
            ])
            ->add('keywords', TextareaType::class, [
                'label' => 'Mots clef (méta)'
            ])
            ->add('lang', ChoiceType::class, [
                'choices' => [
                    'fr' => 'fr',
                    'en' => 'en',
                    'es' => 'es',
                    'de' => 'de',
                    'it' => 'it',
                    'pt' => 'pt',
                    'be' => 'be',
                    'ad' => 'ad',
                    'ro' => 'ro',
                    'ma' => 'ma',
                    'ci' => 'ci'
                ],
                'label' => 'Langue utilisée (a un impact sur la traduction du site)'
            ])
            ->add('activity', null, ['label' => 'Activité en rapport avec le domaine'])
            ->add('homeDescription', TextareaType::class, [
                'attr' => ['rows' => 25],
                'label' => 'Description du site sur la page "home"'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Domain::class,
            'required' => false
        ]);
    }
}
