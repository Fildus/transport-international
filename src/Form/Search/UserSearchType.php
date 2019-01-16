<?php

namespace App\Form\Search;

use App\Entity\Search\UserSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail', EmailType::class, [
                'required' => false,
                'label' => false,
                'attr' => ["placeholder" => 'Email']
            ])
            ->add('role', ChoiceType::class, [
                'required' => false,
                'label' => false,
                'choices' => [
                    'administrateur' => 'ROLE_ADMIN',
                    'visiteur' => 'ROLE_USER',
                    'tous' => null
                ],
                'attr' => ['placeholder' => 'role']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
