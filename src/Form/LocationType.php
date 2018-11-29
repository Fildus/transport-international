<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\ServedZone;
use App\Repository\ServedZoneRepository;
use App\Services\ArrayPath;
use App\Services\Locale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Translation\TranslatorInterface;

class LocationType extends AbstractType
{

    /**
     * @var ServedZoneRepository
     */
    private $servedZoneRepository;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var Locale
     */
    private $locale;

    public function __construct(ServedZoneRepository $servedZoneRepository, TranslatorInterface $translator, Locale $locale)
    {
        $this->servedZoneRepository = $servedZoneRepository;
        $this->translator = $translator;
        $this->locale = $locale;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address')
            ->add('postalCode')
            ->add('city')
            ->add('location', TextType::class)
            ->get('location')
            ->addModelTransformer(new CallbackTransformer(
                function ($object) {
                    dump($object);
                    return $object !== null ? $object->getTranslation() : null;
                },
                function ($string) {
                    /** @var $test ServedZone */
                    $test = $this->servedZoneRepository->getServedZoneNameByDepartment(
                        $string, $this->locale->setLocale()->getLocalematched()
                    );

                    return $test ?? null;
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
