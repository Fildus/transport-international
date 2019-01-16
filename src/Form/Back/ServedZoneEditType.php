<?php

namespace App\Form\Back;

use App\Entity\ServedZone;
use App\Repository\ServedZoneRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServedZoneEditType extends AbstractType
{

    /**
     * @var ServedZoneRepository
     */
    private $servedZoneRepository;
    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(ServedZoneRepository $servedZoneRepository, ObjectManager $objectManager)
    {
        $this->servedZoneRepository = $servedZoneRepository;
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
                    'pays' => ServedZone::COUNTRY,
                    'region' => ServedZone::REGION,
                    'département' => ServedZone::DEPARTMENT
                ]
            ]);
        $builder
            ->add('parent', ChoiceType::class, [
                'choices' => $this->servedZoneRepository->getCountryAndRegionByIdAndTranslation($options['data']->getParent() ?? null),
            ])
            ->get('parent')
            ->addModelTransformer(new CallbackTransformer(
                function ($a) {
                    if ($a !== null) {
                        return $a->getParent();
                    }
                    return null;
                },
                function ($a) {
                    if ($a === null) {
                        return null;
                    }
                    return $this->servedZoneRepository->find($a);
                }
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ServedZone::class
        ]);
    }

}
