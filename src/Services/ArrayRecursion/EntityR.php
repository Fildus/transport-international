<?php

namespace App\Services\ArrayRecursion;

use Doctrine\Common\Collections\ArrayCollection;

class EntityR
{
    /**
     * @var $id integer
     */
    public $id;

    /**
     * @var $parent integer
     */
    public $parent;

    /**
     * @var $level integer
     */
    public $level;

    /**
     * @var $content mixed
     */
    public $content;

    /**
     * @var $children ArrayCollection
     */
    public $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return entityR
     */
    public function setId(int $id): entityR
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getParent(): ?int
    {
        return $this->parent;
    }

    /**
     * @param int $parent
     * @return entityR
     */
    public function setParent(?int $parent): entityR
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $content
     * @return $this
     */
    public function setContent($content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren(): ArrayCollection
    {
        return $this->children;
    }

    /**
     * @param $obj
     * @return $this
     */
    public function addChildren($obj): self
    {
        if (!$this->children->contains($obj)) {
            $this->children->add($obj);
        }
        return $this;
    }

    /**
     * @param $obj
     * @return $this
     */
    public function removeChildren($obj): self
    {
        if ($this->children->contains($obj)) {
            $this->children->remove($obj);
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     * @return EntityR
     */
    public function setLevel(int $level): EntityR
    {
        $this->level = $level;
        return $this;
    }
}