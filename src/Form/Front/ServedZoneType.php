<?php

namespace App\Form\Front;

use App\Entity\Client;
use App\Entity\ServedZone;
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

    private $request;

    public function __construct(ServedZoneRepository $servedZoneRepository)
    {
        $this->servedZoneRepository = $servedZoneRepository;
        $this->request = $this->servedZoneRepository->findBy(['type'=>ServedZone::DEPARTMENT]);
        dump($this->request);
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
                if (isset($event->getData()['servedZone'])) {
                    $formData = $event->getData()['servedZone'];
                }
                foreach ($this->request as $k => $v) {
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
        foreach ($this->request as $item) {
            if (!empty($options['data'])) {
                $servedZone[$item->getId()] = $options['data']->getServedZone()->contains($item) ? true : false;
            }
        }
        dump($options['data']);
        return $servedZone;
    }
}
