<?php

namespace App\Form\Front;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', null, [
                'label' => 'form.contact.phone'
            ])
            ->add('fax', null, [
                'label' => 'form.contact.fax'
            ])
            ->add('contact', null, [
                'label' => 'form.contact.contact'
            ])
            ->add('webSite', null, [
                'label' => 'form.contact.webSite'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
