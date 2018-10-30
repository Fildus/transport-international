<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ManagersRepository")
 */
class Managers
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
    private $companyManager;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $operationsManager;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $salesManager;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyManager(): ?string
    {
        return $this->companyManager;
    }

    public function setCompanyManager(?string $companyManager): self
    {
        $this->companyManager = $companyManager;

        return $this;
    }

    public function getOperationsManager(): ?string
    {
        return $this->operationsManager;
    }

    public function setOperationsManager(?string $operationsManager): self
    {
        $this->operationsManager = $operationsManager;

        return $this;
    }

    public function getSalesManager(): ?string
    {
        return $this->salesManager;
    }

    public function setSalesManager(?string $salesManager): self
    {
        $this->salesManager = $salesManager;

        return $this;
    }
}
