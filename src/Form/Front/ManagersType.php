<?php

namespace App\Form\Front;

use App\Entity\Managers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ManagersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('companyManager', null, [
                'label' => 'form.managers.companyManager'
            ])
            ->add('operationsManager', null, [
                'label' => 'form.managers.operationManager'
            ])
            ->add('salesManager', null, [
                'label' => 'form.managers.salesManager'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Managers::class,
        ]);
    }
}
