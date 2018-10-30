<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Client;
use App\Repository\ActivityRepository;
use App\Repository\ServedZoneRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{

    /**
     * @var ActivityRepository
     */
    private $activityRepository;

    /**
     * @var ServedZoneRepository
     */
    private $servedZoneRepository;

    public function __construct(ActivityRepository $activityRepository, ServedZoneRepository $servedZoneRepository)
    {
        $this->activityRepository = $activityRepository;
        $this->servedZoneRepository = $servedZoneRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('register', RegisterType::class)
//            ->add('legalInformation', LegalInformationType::class)
//            ->add('location', LocationType::class)
//            ->add('contact', ContactType::class)
//            ->add('coreBusiness', CoreBusinessType::class)
//            ->add('managers', ManagersType::class)
//            ->add('equipment', EquipmentType::class)
//            ->add('about', AboutType::class)
            ->add('activity', CollectionType::class, [
                'entry_type' => CheckboxType::class,
                'data' => $this->getActivities($options),
                'mapped' => false,
                'required' => false
            ])
            ->add('servedZone', CollectionType::class, [
                'entry_type' => CheckboxType::class,
                'data' => $this->getServedZones($options),
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

                /**
                 * ServedZone
                 */
                if (isset($event->getData()['servedZone'])) {
                    $formData = $event->getData()['servedZone'];
                }
                foreach ($this->servedZoneRepository->findBy(['country' => null, 'region' => null]) as $k => $v) {
                    if (isset($formData[$v->getDepartment()])) {
                        $event->getForm()->getNormData()->addServedZone($v);
                    } else {
                        $event->getForm()->getNormData()->removeServedZone($v);
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

    public function getServedZones($options)
    {
        dump($options['data']);
        $servedZone = [];
        foreach ($this->servedZoneRepository->findBy(['country' => null, 'region' => null]) as $item) {
            if (!empty($options['data'])) {
                $servedZone[$item->getDepartment()] = $options['data']->getServedZone()->contains($item) ? true : false;
            }
        }
        return $servedZone;
    }
}
