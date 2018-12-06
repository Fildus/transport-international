<?php

namespace App\Form\Front;

use App\Entity\CoreBusiness;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoreBusinessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('transport')
            ->add('logistics')
            ->add('charter')
            ->add('travelers')
            ->add('relocation')
            ->add('storage')
            ->add('rentalWithDriver')
            ->add('taxis')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CoreBusiness::class,
        ]);
    }
}
