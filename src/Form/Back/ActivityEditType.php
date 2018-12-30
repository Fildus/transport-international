<?php

namespace App\Form\Back;

use App\Entity\Activity;
use App\Repository\ActivityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityEditType extends AbstractType
{

    /**
     * @var ActivityRepository
     */
    private $activityRepository;
    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(ActivityRepository $activityRepository, ObjectManager $objectManager)
    {
        $this->activityRepository = $activityRepository;
        $this->objectManager = $objectManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translation', TranslationType::class, [
                'label' => false
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'activité' => Activity::PATH,
                    'prestation' => Activity::ACTIVITY,
                ]
            ]);
        $builder
            ->add('parent', ChoiceType::class, [
                'choices' => $this->activityRepository->getPathById($options['data']->getParent() ?? null),
            ])
            ->get('parent')
            ->addModelTransformer(new CallbackTransformer(
                function ($a) {
                    if ($a !== null) {
                        return $a;
                    }
                    return null;
                },
                function ($a) {
                    if ($a === null) {
                        return null;
                    }
                    return $this->activityRepository->find($a);
                }
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class
        ]);
    }

}
