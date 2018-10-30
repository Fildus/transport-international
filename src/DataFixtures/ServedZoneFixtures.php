<?php

namespace App\DataFixtures;

use App\Entity\ServedZone;
use App\Repository\ServedZoneRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class ServedZoneFixtures extends Fixture
{

    /**
     * @var ServedZoneRepository
     */
    private $sZR;

    public function __construct(ServedZoneRepository $sZR)
    {
        $this->sZR = $sZR;
    }

    public function load(ObjectManager $manager)
    {
        $datas = Yaml::parseFile(realpath('src/Data/servedZone.yaml'));
        foreach ($datas as $k => $v) {
            $sC = new ServedZone();
            $sC->setCountry($k);
            $manager->persist($sC);
            foreach ($v as $kb => $vb) {
                $sR = new ServedZone();
                $sR->setRegion($kb);
                $sR->setParent($sC);
                $manager->persist($sR);
                if (is_string($vb)){
                    $sD = new ServedZone();
                    $sD->setDepartment($vb);
                    $sD->setParent($sR);
                    $manager->persist($sD);
                }else{
                    foreach ($vb as $kc => $vc) {
                        $sD = new ServedZone();
                        $sD->setDepartment($vc);
                        $sD->setParent($sR);
                        $manager->persist($sD);
                    }
                }
            }
        }
        $manager->flush();
    }
}
