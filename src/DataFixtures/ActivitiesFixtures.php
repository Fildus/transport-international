<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Translation;
use App\Repository\ActivityRepository;
use App\Services\ArrayPath;
use App\Services\Slug;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class ActivitiesFixtures extends Fixture
{

    /**
     * @var ActivityRepository
     */
    private $aR;

    private $translations;
    /**
     * @var Slug
     */
    private $slug;

    public function __construct(ActivityRepository $aR, Slug $slug)
    {
        $this->aR = $aR;
        $this->slug = $slug;
    }

    public function load(ObjectManager $manager)
    {
        $data = Yaml::parseFile(realpath('src/Data/activities.yaml'));
        $this->translations = (new ArrayPath(
            Yaml::parseFile(realpath('src/Data/activitiesForm.fr.yaml')),
            '__',
            [
                'labelName',
                'activitiesForm'
            ]
        ))->getArrayLvl();
        $this->iterator($manager, $data);
    }

    /**
     * @param ObjectManager $manager
     * @param $data
     * @param null $parent
     */
    private function iterator($manager, $data, $parent = null)
    {
        foreach ($data as $k => $v) {
            $activity = new Activity();
            if (!is_string($v)) {
                $activity->setPath($k);
                $activity = $this->addTranslationSlug($k, $activity);
                if ($parent !== null) {
                    $activity->setParent($parent);
                }
                $this->iterator($manager, $v, $activity);
            } else {
                $activity->setName($v);
                $activity = $this->addTranslationSlug($v, $activity);
                if ($parent !== null) {
                    $activity->setParent($parent);
                }
            }
            $manager->persist($activity);
            $manager->flush();
        }
    }

    /**
     * @param $key
     * @param $activity
     * @return Activity
     */
    public function addTranslationSlug($key, $activity)
    {
        foreach ($this->translations as $item) {
            $countKey = count($item['path']) - 2;
            $arrayKey = $item['path'][$countKey];
            $arrayValue = $item['path'][$countKey + 1];
            if ($arrayKey === $key) {
                $fr = $arrayValue;
            }
        }

        if (isset($fr)) {
            /** @var $activity Activity */
            $translation = new Translation();
            $translation
                ->setFr($fr)
                ->setFrSlug($this->slug->getSlug($fr))
                ->setEn($fr)
                ->setEnSlug($this->slug->getSlug($fr))
                ->setEs($fr)
                ->setEsSlug($this->slug->getSlug($fr))
                ->setDe($fr)
                ->setDeSlug($this->slug->getSlug($fr))
                ->setIt($fr)
                ->setItSlug($this->slug->getSlug($fr))
                ->setPt($fr)
                ->setPtSlug($this->slug->getSlug($fr))
                ->setBe($fr)
                ->setBeSlug($this->slug->getSlug($fr))
                ->setRo($fr)
                ->setRoSlug($this->slug->getSlug($fr))
                ->setMa($fr)
                ->setMaSlug($this->slug->getSlug($fr))
                ->setCi($fr)
                ->setCiSlug($this->slug->getSlug($fr))
                ->setAd($fr)
                ->setAdSlug($this->slug->getSlug($fr));
            $activity = $activity->setTranslation($translation);
        }
        return $activity;
    }
}
