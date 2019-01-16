<?php

namespace App\Form\Front;

use App\Entity\LegalInformation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LegalInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('siret')
            ->add('corporateName', null, [
                'label' => 'form.legalInformations.siret'
            ])
            ->add('companyName', null, [
                'label' => 'form.legalInformations.CompanyName'
            ])
            ->add('legalForm', ChoiceType::class, [
                'choices' => ['S.A' => 'S.A', 'S.A.R.L' => 'S.A.R.L', 'E.U.R.L' => 'E.U.R.L', 'N.P' => 'N.P'],
                'label' => 'form.legalInformations.LegamForm'
            ])
            ->add('turnover', null, [
                'label' => 'form.legalInformations.Turnover'
            ])
            ->add('workforceNbr', null, [
                'label' => 'form.legalInformations.workforceNbr'
            ])
            ->add('establishmentsNbr', null, [
                'label' => 'form.legalInformations.EstablishmentNbr'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LegalInformation::class,
        ]);
    }
}
