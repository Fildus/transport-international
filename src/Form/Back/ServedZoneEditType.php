<?php

namespace App\Form\Back;

use App\Entity\ServedZone;
use App\Repository\ServedZoneRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServedZoneEditType extends AbstractType
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
            ->add('translation', TranslationType::class, [
                'label' => false
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'pays' => ServedZone::COUNTRY,
                    'region' => ServedZone::REGION,
                    'département' => ServedZone::DEPARTMENT
                ]
            ])
            ->add('indicative', IntegerType::class, [
                'label' => 'indicatif du pays'
            ]);
        $builder
            ->add('parent', ChoiceType::class, [
                'choices' => $this->servedZoneRepository->getCountryAndRegionByIdAndTranslation($options['data']->getParent() ?? null),
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
