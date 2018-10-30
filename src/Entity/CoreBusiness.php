<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoreBusinessRepository")
 */
class CoreBusiness
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $transport;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $logistics;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $charter;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $travelers;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $relocation;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $storage;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $rentalWithDriver;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $taxis;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransport(): ?bool
    {
        return $this->transport;
    }

    public function setTransport(bool $transport): self
    {
        $this->transport = $transport;

        return $this;
    }

    public function getLogistics(): ?bool
    {
        return $this->logistics;
    }

    public function setLogistics(?bool $logistics): self
    {
        $this->logistics = $logistics;

        return $this;
    }

    public function getCharter(): ?bool
    {
        return $this->charter;
    }

    public function setCharter(bool $charter): self
    {
        $this->charter = $charter;

        return $this;
    }

    public function getTravelers(): ?bool
    {
        return $this->travelers;
    }

    public function setTravelers(bool $travelers): self
    {
        $this->travelers = $travelers;

        return $this;
    }

    public function getRelocation(): ?bool
    {
        return $this->relocation;
    }

    public function setRelocation(bool $relocation): self
    {
        $this->relocation = $relocation;

        return $this;
    }

    public function getStorage(): ?bool
    {
        return $this->storage;
    }

    public function setStorage(bool $storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    public function getRentalWithDriver(): ?bool
    {
        return $this->rentalWithDriver;
    }

    public function setRentalWithDriver(bool $rentalWithDriver): self
    {
        $this->rentalWithDriver = $rentalWithDriver;

        return $this;
    }

    public function getTaxis(): ?bool
    {
        return $this->taxis;
    }

    public function setTaxis(bool $taxis): self
    {
        $this->taxis = $taxis;

        return $this;
    }
}
