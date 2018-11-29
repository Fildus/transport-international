<?php

namespace App\Form;

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
            ->add('corporateName')
            ->add('companyName')
            ->add('legalForm', ChoiceType::class, [
                'choices' => ['S.A' => 'S.A', 'S.A.R.L' => 'S.A.R.L', 'E.U.R.L' => 'E.U.R.L', 'N.P' => 'N.P']
            ])
            ->add('turnover')
            ->add('workforceNbr')
            ->add('establishmentsNbr');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LegalInformation::class,
        ]);
    }
}
