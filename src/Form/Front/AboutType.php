<?php

namespace App\Form\Front;

use App\Entity\About;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AboutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isoCertification', null, [
                'label' => 'form.about.isoCertification'
            ])
            ->add('summary', null, [
                'label' => 'form.about.summary'
            ])
            ->add('rangeAction', null, [
                'label' => 'form.about.rangeAction'
            ])
            ->add('services', null, [
                'label' => 'form.about.services'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => About::class,
        ]);
    }
}
