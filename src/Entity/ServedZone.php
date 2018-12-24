<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServedZoneRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ServedZone
{
    const COUNTRY = 1;
    const REGION = 2;
    const DEPARTMENT = 3;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $department;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="ServedZone", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\Column(type="smallint", type="integer")
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity="ServedZone", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Translation", cascade={"persist", "remove"})
     */
    private $translation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Client", mappedBy="servedZone")
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
        if ($parent == null) {
            $this->level = 0;
            $this->parent = null;
        } else {
            $this->level = $parent->getLevel() + 1;
            $this->parent = $parent;
        }

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

    public function addChildren(ServedZone $servedZone): self
    {
        if (!$this->children->contains($servedZone)) {
            $this->children[] = $servedZone;
        }
        return $this;
    }

    public function removeChildren(ServedZone $servedZone): self
    {
        if ($this->children->contains($servedZone)) {
            $this->children->remove($servedZone);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->getTranslation();
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

    /**
     * @return Collection
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
     * @return ServedZone
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return ServedZone
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     * @return ServedZone
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $department
     * @return ServedZone
     */
    public function setDepartment($department)
    {
        $this->department = $department;
        return $this;
    }

    /**
     * @ORM\PreFlush()
     */
    public function preFlush()
    {
        if ($this->parent === null){
            $this->level = 0;
        }
    }

}
