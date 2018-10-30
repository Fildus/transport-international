<?php

namespace App\Repository;

use App\Entity\ServedZone;
use Doctrine\Common\Persistence\ManagerRegistry;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class ServedZoneRepository extends NestedTreeRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $entityClass = ServedZone::class;

        $manager = $registry->getManagerForClass($entityClass);

        parent::__construct($manager, $manager->getClassMetadata($entityClass));
    }
}
