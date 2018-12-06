<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="App\Repository\ActivityRepository")
 */
class Activity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(length=64, nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(length=64, nullable=true)
     */
    private $name;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="parent", fetch="EAGER")
     */
    private $children;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Translation", cascade={"persist", "remove"})
     */
    private $translation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Client", mappedBy="activity")
     */
    private $clients;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->clients = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     * @return Activity
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function addChildren(Activity $activity): self
    {
        if (!$this->children->contains($activity)){
            $this->children[] = $activity;
        }
        return $this;
    }

    public function removeChildren(Activity $activity): self
    {
        if ($this->children->contains($activity)){
            $this->children->remove($activity);
        }
        return $this;
    }

    public function getLeft()
    {
        return $this->lft;
    }

    public function getRight()
    {
        return $this->rgt;
    }

    public function getTranslation()
    {
        return $this->translation;
    }

    public function setTranslation($translation): self
    {
        $this->translation = $translation;

        return $this;
    }

    public function __toString()
    {
        return $this->getName() ?? $this->getPath();
    }

    /**
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->addActivity($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->contains($client)) {
            $this->clients->removeElement($client);
            $client->removeActivity($this);
        }

        return $this;
    }
}
