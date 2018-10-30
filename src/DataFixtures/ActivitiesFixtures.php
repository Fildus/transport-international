<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Repository\ActivityRepository;
use App\Services\ArrayPath;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class ActivitiesFixtures extends Fixture
{

    /**
     * @var ActivityRepository
     */
    private $aR;

    public function __construct(ActivityRepository $aR)
    {
        $this->aR = $aR;
    }

    public function load(ObjectManager $manager)
    {
        $data = Yaml::parseFile(realpath('src/Data/activities.yaml'));
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
                if ($parent !== null){
                    $activity->setParent($parent);
                }
                $this->iterator($manager, $v, $activity);
            } else {
                $activity->setName($v);
                if ($parent !== null){
                    $activity->setParent($parent);
                }
            }
            $manager->persist($activity);
            $manager->flush();
        }
    }
}
