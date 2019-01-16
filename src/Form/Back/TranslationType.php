<?php

namespace App\Form\Back;

use App\Entity\Translation;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fr', TextType::class)
            ->add('frSlug', TextType::class, [
                'disabled' => true
            ])
            ->add('en', TextType::class)
            ->add('enSlug', TextType::class, [
                'disabled' => true
            ])
            ->add('es', TextType::class)
            ->add('esSlug', TextType::class, [
                'disabled' => true
            ])
            ->add('de', TextType::class)
            ->add('deSlug', TextType::class, [
                'disabled' => true
            ])
            ->add('it', TextType::class)
            ->add('itSlug', TextType::class, [
                'disabled' => true
            ])
            ->add('pt', TextType::class)
            ->add('ptSlug', TextType::class, [
                'disabled' => true
            ])
            ->add('be', TextType::class)
            ->add('beSlug', TextType::class, [
                'disabled' => true
            ])
            ->add('ad', TextType::class)
            ->add('adSlug', TextType::class, [
                'disabled' => true
            ])
            ->add('ro', TextType::class)
            ->add('roSlug', TextType::class, [
                'disabled' => true
            ])
            ->add('ma', TextType::class)
            ->add('maSlug', TextType::class, [
                'disabled' => true
            ])
            ->add('ci', TextType::class)
            ->add('ciSlug', TextType::class, [
                'disabled' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Translation::class,
            'required' => false
        ]);
    }
}
