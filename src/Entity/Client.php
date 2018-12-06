<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\LegalInformation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $legalInformation;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Location", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $location;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Contact", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $contact;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CoreBusiness", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $coreBusiness;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Managers", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $managers;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Equipment", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $equipment;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\About", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $about;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ServedZone", inversedBy="clients")
     */
    private $servedZone;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Activity", inversedBy="clients", fetch="EAGER")
     */
    private $activity;

    public function __construct()
    {
        $this->servedZone = new ArrayCollection();
        $this->activity = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLegalInformation(): ?LegalInformation
    {
        return $this->legalInformation;
    }

    public function setLegalInformation(LegalInformation $legalInformation): self
    {
        $this->legalInformation = $legalInformation;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getCoreBusiness(): ?CoreBusiness
    {
        return $this->coreBusiness;
    }

    public function setCoreBusiness(CoreBusiness $coreBusiness): self
    {
        $this->coreBusiness = $coreBusiness;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getManagers(): ?Managers
    {
        return $this->managers;
    }

    public function setManagers(Managers $managers): self
    {
        $this->managers = $managers;

        return $this;
    }

    public function getEquipment(): ?Equipment
    {
        return $this->equipment;
    }

    public function setEquipment(Equipment $equipment): self
    {
        $this->equipment = $equipment;

        return $this;
    }

    public function getAbout(): ?About
    {
        return $this->about;
    }

    public function setAbout(About $about): self
    {
        $this->about = $about;

        return $this;
    }

    /**
     * @return Collection|ServedZone[]
     */
    public function getServedZone(): Collection
    {
        return $this->servedZone;
    }

    public function addServedZone(ServedZone $servedZone): self
    {
        if (!$this->servedZone->contains($servedZone)) {
            $this->servedZone[] = $servedZone;
        }

        return $this;
    }

    public function removeServedZone(ServedZone $servedZone): self
    {
        if ($this->servedZone->contains($servedZone)) {
            $this->servedZone->removeElement($servedZone);
        }

        return $this;
    }

    /**
     * @return Collection|Activity[]
     */
    public function getActivity(): Collection
    {
        return $this->activity;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activity->contains($activity)) {
            $this->activity[] = $activity;
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activity->contains($activity)) {
            $this->activity->removeElement($activity);
        }

        return $this;
    }
       
}
