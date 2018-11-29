<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="App\Repository\ServedZoneRepository")
 * @ORM\Cache(usage="READ_ONLY")
 */
class ServedZone
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
    private $country;

    /**
     * @ORM\Column(length=64, nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(length=64, nullable=true)
     */
    private $department;

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
     * @ORM\ManyToOne(targetEntity="ServedZone", inversedBy="children", cascade={"persist"})
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

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getRegion()
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getDepartment()
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
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

    public function addChildren(ServedZone $servedZone): self
    {
        if (!$this->children->contains($servedZone)){
            $this->children[] = $servedZone;
        }
        return $this;
    }

    public function removeChildren(ServedZone $servedZone): self
    {
        if ($this->children->contains($servedZone)){
            $this->children->remove($servedZone);
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

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->getDepartment() ?? $this->getRegion() ?? $this->getCountry();
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
}
