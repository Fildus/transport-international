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
            ->add('transport', null, [
                'label' => 'form.coreBusiness.transport'
            ])
            ->add('logistics', null, [
                'label' => 'form.coreBusiness.logistics'
            ])
            ->add('charter', null, [
                'label' => 'form.coreBusiness.charter'
            ])
            ->add('travelers', null, [
                'label' => 'form.coreBusiness.travelers'
            ])
            ->add('relocation', null, [
                'label' => 'form.coreBusiness.relocation'
            ])
            ->add('storage', null, [
                'label' => 'form.coreBusiness.storage'
            ])
            ->add('rentalWithDriver', null, [
                'label' => 'form.coreBusiness.rentalWithDriver'
            ])
            ->add('taxis', null, [
                'label' => 'form.coreBusiness.taxis'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CoreBusiness::class,
        ]);
    }
}
