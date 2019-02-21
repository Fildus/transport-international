<?php

namespace App\Entity;

use App\Services\Slug;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LegalInformationRepository")
 * @ORM\HasLifecycleCallbacks()
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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

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

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @throws \Exception
     */
    public function setSlugLifecycleCallback(): void
    {
        if (empty($this->slug)) {
            $this->slug = (new Slug())->getSlug($this->companyName ?? $this->corporateName) . '-' . random_int(1000, 10000);
        }
    }

//    /**
//     * @param ExecutionContextInterface $context
//     * @Assert\Callback()
//     */
//    public function siretValidation(ExecutionContextInterface $context)
//    {
//        $v = $context->getValue()->getSiret();
//        $length = 14;
//        if (strlen($v) !== $length && $v !== null) {
//            $context
//                ->buildViolation('form.legalInformations.validation.siret')
//                ->setTranslationDomain('messages')
//                ->setParameter('{{ length }}', $length)
//                ->addViolation();
//        }
//    }

//    /**
//     * @param ExecutionContextInterface $context
//     * @Assert\Callback()
//     */
//    public function corporateNameValidation(ExecutionContextInterface $context)
//    {
//        $v = $context->getValue()->getCorporateName();
//        $min = 3;
//        $max = 255;
//        if (strlen($v) < $min || strlen($v) > $max) {
//            $context
//                ->buildViolation('form.legalInformations.validation.corporateName')
//                ->setTranslationDomain('messages')
//                ->setParameter('{{ min }}', $min)
//                ->setParameter('{{ max }}', $max)
//                ->addViolation();
//        }
//    }

//    /**
//     * @param ExecutionContextInterface $context
//     * @Assert\Callback()
//     */
//    public function companyNameValidation(ExecutionContextInterface $context)
//    {
//        $v = $context->getValue()->getCompanyName();
//        $min = 3;
//        $max = 255;
//        if (strlen($v) < $min || strlen($v) > $max) {
//            $context
//                ->buildViolation('form.legalInformations.validation.CompanyName')
//                ->setTranslationDomain('messages')
//                ->setParameter('{{ min }}', $min)
//                ->setParameter('{{ max }}', $max)
//                ->addViolation();
//        }
//    }

//    /**
//     * @param ExecutionContextInterface $context
//     * @Assert\Callback()
//     */
//    public function legalFormValidation(ExecutionContextInterface $context)
//    {
//        $v = $context->getValue()->getLegalForm();
//        $choices = ['S.A', 'S.A.R.L', 'E.U.R.L', 'N.P'];
//        if (!in_array($v, $choices)) {
//            $context
//                ->buildViolation('form.legalInformations.validation.corporateName')
//                ->addViolation();
//        }
//    }

//    /**
//     * @param ExecutionContextInterface $context
//     * @Assert\Callback()
//     */
//    public function turnoverValidation(ExecutionContextInterface $context)
//    {
//        $v = $context->getValue()->getLegalForm();
//        if (!in_array($v, $choices)) {
//            $context
//                ->buildViolation('form.legalInformations.validation.corporateName')
//                ->addViolation();
//        }
//    }
}
