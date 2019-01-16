<?php

namespace App\Form\Back;

use App\Entity\Contract;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'email'
            ])
            ->add('bill', TextType::class, [
                'label' => 'contract'
            ])
            ->add('amount', TextType::class, [
                'label' => 'montant'
            ])
            ->add('currency', TextType::class, [
                'label' => 'devise'
            ])
            ->add('duration', TextType::class, [
                'label' => 'durée'
            ])
            ->add('dateBc', DateType::class, [
                'label' => 'date bc'
            ])
            ->add('datePaiement', DateType::class, [
                'label' => 'date paiement'
            ])
            ->add('mode', TextType::class, [
                'label' => 'mode'
            ])
            ->add('comment', TextType::class, [
                'label' => 'commentaire'
            ])
            ->add('litigation', ChoiceType::class, [
                'label' => 'litige',
                'choices' => [
                    'oui' => 0,
                    'non' => 1
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'status',
                'choices' => [
                    'oui' => 0,
                    'non' => 1
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contract::class,
        ]);
    }
}
