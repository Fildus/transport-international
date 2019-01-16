<?php

namespace App\Services\ArrayRecursion;

use Doctrine\Common\Collections\ArrayCollection;


/**
 * @author  david GASTALDELLO davidgastaldello@msn.com
 * Interface RecursionInterface
 * @package App\Services\ArrayRecursion
 */
interface RecursionInterface
{
    /**
     * Permet d'itérer à partir des enfants afin de rétablir une hierarchie
     *
     * Chaque object doit contenir les méthodes
     *      getChildren() $obj
     *      getParent() array|ArrayCollection $obj
     *      getLevel() integer
     *      getId() integer
     *
     * @param ArrayCollection $collection
     *
     * @return ArrayCollection $items
     */
    public function run($collection);
}