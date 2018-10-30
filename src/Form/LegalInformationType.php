<?php

namespace App\Form;

use App\Entity\LegalInformation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LegalInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('siret')
            ->add('corporateName')
            ->add('companyName')
            ->add('legalForm')
            ->add('turnover')
            ->add('workforceNbr')
            ->add('establishmentsNbr')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LegalInformation::class,
        ]);
    }
}
