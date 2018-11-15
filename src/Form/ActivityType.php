<?php

namespace App\Form;

use App\Entity\Client;
use App\Repository\ActivityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{

    /**
     * @var ActivityRepository
     */
    private $activityRepository;

    public function __construct(ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('activity', CollectionType::class, [
                'entry_type' => CheckboxType::class,
                'data' => $this->getActivities($options),
                'mapped' => false,
                'required' => false
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                /**
                 * Activities
                 */
                if (isset($event->getData()['activity'])) {
                    $formData = $event->getData()['activity'];
                }
                foreach ($this->activityRepository->findBy(['path' => null]) as $k => $v) {
                    if (isset($formData[$v->getName()])) {
                        $event->getForm()->getNormData()->addActivity($v);
                    } else {
                        $event->getForm()->getNormData()->removeActivity($v);
                    }
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }

    public function getActivities($options)
    {
        $activites = [];
        foreach ($this->activityRepository->findBy(['path' => null]) as $item) {
            if (!empty($options['data'])) {
                $activites[$item->getName()] = $options['data']->getActivity()->contains($item) ? true : false;
            }
        }
        return $activites;
    }
}
