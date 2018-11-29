<?php

namespace App\DataFixtures;

use App\Entity\ServedZone;
use App\Entity\Translation;
use App\Repository\ServedZoneRepository;
use App\Services\ArrayPath;
use App\Services\Slug;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class ServedZoneFixtures extends Fixture
{

    /**
     * @var ServedZoneRepository
     */
    private $sZR;

    private $translations;
    /**
     * @var Slug
     */
    private $slug;

    public function __construct(ServedZoneRepository $sZR, Slug $slug)
    {
        $this->sZR = $sZR;
        $this->translations = (new ArrayPath(
            Yaml::parseFile(realpath('src/Data/servedZoneForm.fr.yaml')),
            '__',
            [
                'labelName',
                'servedZoneForm'
            ]
        ))->getArrayLvl();
        $this->slug = $slug;
    }

    public function load(ObjectManager $manager)
    {
        $datas = Yaml::parseFile(realpath('src/Data/servedZone.yaml'));
        foreach ($datas as $k => $v) {
            $sC = new ServedZone();
            $sC->setCountry($k);
            $sC = $this->addTranslation($k, $sC);
            $manager->persist($sC);
            foreach ($v as $kb => $vb) {
                $sR = new ServedZone();
                $sR->setRegion($kb);
                $sR->setParent($sC);
                $sR = $this->addTranslation($kb, $sR);
                $manager->persist($sR);
                if (is_string($vb)) {
                    $sD = new ServedZone();
                    $sD->setDepartment($vb);
                    $sD->setParent($sR);
                    $sD = $this->addTranslation($vb, $sD);
                    $manager->persist($sD);
                } else {
                    foreach ($vb as $kc => $vc) {
                        $sD = new ServedZone();
                        $sD->setDepartment($vc);
                        $sD->setParent($sR);
                        $sD = $this->addTranslation($vc, $sD);
                        $manager->persist($sD);
                    }
                }
            }
        }
        $manager->flush();
    }

    /**
     * @param $key
     * @param $servedZone
     * @return ServedZone
     */
    public function addTranslation($key, $servedZone)
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
            /** @var $servedZone ServedZone */
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
            $servedZone = $servedZone->setTranslation($translation);
        }
        return $servedZone;
    }
}
