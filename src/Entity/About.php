<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AboutRepository")
 */
class About
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $isoCertification;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rangeAction;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $services;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsoCertification(): ?string
    {
        return $this->isoCertification;
    }

    public function setIsoCertification(?string $isoCertification): self
    {
        $this->isoCertification = $isoCertification;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getRangeAction(): ?string
    {
        return $this->rangeAction;
    }

    public function setRangeAction(?string $rangeAction): self
    {
        $this->rangeAction = $rangeAction;

        return $this;
    }

    public function getServices(): ?string
    {
        return $this->services;
    }

    public function setServices(?string $services): self
    {
        $this->services = $services;

        return $this;
    }
}
