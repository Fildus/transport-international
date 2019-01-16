<?php

namespace App\Services\ArrayRecursion;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author  david GASTALDELLO davidgastaldello@msn.com
 *
 * Class Recursion
 * @package App\Services\ArrayRecursion
 */
class Recursion implements RecursionInterface
{
    /**
     * @var $result ArrayCollection
     */
    private $result;

    /**
     * @param ArrayCollection $objects
     *
     * @return ArrayCollection|mixed
     */
    public function run($objects)
    {
        $this->result = new ArrayCollection();

        foreach ($objects as $obj) {
            $this->makeEntityRIterator($obj);
        }

        /**
         * @var $k    int
         * @var $item EntityR
         */
        foreach ($this->result as $k => $item) {
            if ($item->getParent() !== null && isset($this->result[$item->getParent()])) {
                $this->result[$item->getParent()]->addChildren($item);
            }
        }

        foreach ($this->result as $k => $item) {
            if ($item->getChildren()->isEmpty()) {
                $this->result->remove($k);
            }
            if ($item->getLevel() !== 0) {
                $this->result->remove($k);
            }
        }

        return $this->result;
    }

    /**
     * @param $obj
     *
     * @return Recursion
     */
    private function makeEntityRIterator($obj): self
    {
        $entityR = new EntityR();
        $entityR
            ->setId($obj->getId())
            ->setParent($obj->getParent() !== null ? $obj->getParent()->getId() : null)
            ->setLevel($obj->getLevel())
            ->setContent($obj);

        $this->result[$obj->getId()] = $entityR;

        if ($obj->getParent() !== null) {
            if (!$this->result->contains($obj->getParent())) {
                $this->makeEntityRIterator($obj->getParent());
            }
        }

        return $this;
    }

}