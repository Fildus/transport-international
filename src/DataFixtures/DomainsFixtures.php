<?php

namespace App\DataFixtures;

use App\Entity\Domain;
use App\Repository\ActivityRepository;
use Symfony\Component\Yaml\Yaml;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DomainsFixtures extends Fixture
{

    /**
     * @var mixed
     */
    private $domains;
    /**
     * @var ActivityRepository
     */
    private $activityRepository;

    public function __construct(ActivityRepository $activityRepository)
    {
        $this->domains = Yaml::parseFile(realpath('src/Data/domain.yaml'));
        $this->activityRepository = $activityRepository;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->domains as $domain) {
            $domainEntity = new Domain();
            $domainEntity
                ->setDomain($domain['domain'])
                ->setTitle($domain['title'])
                ->setDescription($domain['description'])
                ->setKeywords($domain['keywords'])
                ->setLang($domain['lang']);
            if ($domain['select'] !== null) {
                $domainEntity->setActivity(
                    $this->activityRepository->findOneByName($domain['select']) ??
                    $this->activityRepository->findOneByPath($domain['select'])
                );
            }
            $manager->persist($domainEntity);
        }
        $manager->flush();
    }

}
