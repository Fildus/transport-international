<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Common\Persistence\ManagerRegistry;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class ActivityRepository extends NestedTreeRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $entityClass = Activity::class;

        $manager = $registry->getManagerForClass($entityClass);

        parent::__construct($manager, $manager->getClassMetadata($entityClass));
    }
}
