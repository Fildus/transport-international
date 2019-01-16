<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActivityRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Activity
{
    public const PATH = 1;
    public const ACTIVITY = 2;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="children", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\Column(type="smallint", name="lvl", type="integer")
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="parent", fetch="EAGER")
     */
    public $children;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Translation", cascade={"persist", "remove"}, fetch="EAGER")
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

    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setLevel(int $level)
    {
        $this->level = $level;

        return $this;
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
        if (!$this->children->contains($activity)) {
            $this->children[] = $activity;
        }
        return $this;
    }

    public function removeChildren(Activity $activity): self
    {
        if ($this->children->contains($activity)) {
            $this->children->remove($activity);
        }
        return $this;
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

    public function __toString(): string
    {
        return $this->translation->__toString();
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

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     *
     * @return Activity
     */
    public function setType($type)
    {
        $this->type = $type;

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
     *
     * @return Activity
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return Activity
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @ORM\PreFlush()
     */
    public function PreFlush()
    {
        if ($this->parent !== null) {
            $this->level = ($this->getParent()->getLevel() ?? 0) + 1;
        } else {
            $this->level = 0;
        }
    }
}
