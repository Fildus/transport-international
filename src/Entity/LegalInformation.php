<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LegalInformationRepository")
 */
class LegalInformation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint", options={"unsigned" = true}, nullable=true)
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $corporateName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $legalForm;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $turnover;

    /**
     * @ORM\Column(type="integer", options={"unsigned" = true}, nullable=true)
     */
    private $workforceNbr;

    /**
     * @ORM\Column(type="smallint", options={"unsigned" = true}, nullable=true)
     */
    private $establishmentsNbr;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSiret(): ?int
    {
        return $this->siret;
    }

    public function setSiret(?int $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getCorporateName(): ?string
    {
        return $this->corporateName;
    }

    public function setCorporateName(?string $corporateName): self
    {
        $this->corporateName = $corporateName;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getLegalForm(): ?string
    {
        return $this->legalForm;
    }

    public function setLegalForm(?string $legalForm): self
    {
        $this->legalForm = $legalForm;

        return $this;
    }

    public function getTurnover()
    {
        return $this->turnover;
    }

    public function setTurnover($turnover): self
    {
        $this->turnover = $turnover;

        return $this;
    }

    public function getWorkforceNbr(): ?int
    {
        return $this->workforceNbr;
    }

    public function setWorkforceNbr(?int $workforceNbr): self
    {
        $this->workforceNbr = $workforceNbr;

        return $this;
    }

    public function getEstablishmentsNbr(): ?int
    {
        return $this->establishmentsNbr;
    }

    public function setEstablishmentsNbr(?int $establishmentsNbr): self
    {
        $this->establishmentsNbr = $establishmentsNbr;

        return $this;
    }
}
