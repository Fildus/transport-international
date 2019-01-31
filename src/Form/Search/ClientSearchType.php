<?php

namespace App\Form\Search;

use App\Entity\Search\ClientSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('siret', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => ["placeholder" => 'Siret']
            ])
            ->add('corporateName', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ["placeholder" => 'Corporation']
            ])
            ->add('companyName', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ["placeholder" => 'Companie']
            ])
            ->add('legalForm', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ["placeholder" => 'Nom légal']
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ["placeholder" => 'Adresse']
            ])
            ->add('postalCode', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ["placeholder" => 'Code postal']
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ["placeholder" => 'ville']
            ])
            ->add('location', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ["placeholder" => 'localisation']
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ["placeholder" => 'Téléphone']
            ])
            ->add('fax', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ["placeholder" => 'Fax']
            ])
            ->add('contact', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ["placeholder" => 'Email de contact']
            ])
            ->add('webSite', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ["placeholder" => 'Site web']
            ])
            ->add('contract', ChoiceType::class, [
                'required' => false,
                'label' => false,
                'attr' => ["placeholder" => 'Dispose d\'un contract ?'],
                'choices' => [
                    'Indifférent' => null,
                    'Posséde un contract' => 1,
                    'Pas de contracts' => 0
                ]
            ])
            ->add('haveEmail', ChoiceType::class, [
                'required' => false,
                'label' => false,
                'attr'=>['placeholder' => 'Dispose d\'un email?'],
                'choices' => [
                    'Indifférent' => null,
                    'Possède un email' => 1,
                    'Ne possède pas d\'email' => 0
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ClientSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
