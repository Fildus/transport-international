<?php

namespace App\Form;

use App\Entity\Client;
use App\Repository\ServedZoneRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServedZoneType extends AbstractType
{

    /**
     * @var ServedZoneRepository
     */
    private $servedZoneRepository;

    public function __construct(ServedZoneRepository $servedZoneRepository)
    {
        $this->servedZoneRepository = $servedZoneRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('servedZone', CollectionType::class, [
                'entry_type' => CheckboxType::class,
                'data' => $this->getServedZones($options),
                'mapped' => false,
                'required' => false
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
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

    public function getServedZones($options)
    {
        $servedZone = [];
        foreach ($this->servedZoneRepository->findBy(['country' => null, 'region' => null]) as $item) {
            if (!empty($options['data'])) {
                $servedZone[$item->getDepartment()] = $options['data']->getServedZone()->contains($item) ? true : false;
            }
        }
        return $servedZone;
    }
}
