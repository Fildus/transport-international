<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EquipmentRepository")
 */
class Equipment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $vehiclesNbr;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $materials;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVehiclesNbr(): ?int
    {
        return $this->vehiclesNbr;
    }

    public function setVehiclesNbr(?int $vehiclesNbr): self
    {
        $this->vehiclesNbr = $vehiclesNbr;

        return $this;
    }

    public function getMaterials(): ?string
    {
        return $this->materials;
    }

    public function setMaterials(?string $materials): self
    {
        $this->materials = $materials;

        return $this;
    }
}
