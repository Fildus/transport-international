<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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

//    /**
//     * @param ExecutionContextInterface $context
//     * @Assert\Callback()
//     */
//    public function isoCertificationValidation(ExecutionContextInterface $context)
//    {
//        $v = $context->getValue()->getIsoCertification();
//        $min = 5;
//        $max = 255;
//        if ((strlen($v) < $min || strlen($v) > $max) && $v !== null) {
//            $context
//                ->buildViolation('form.about.validation.isoCertification')
//                ->setTranslationDomain('messages')
//                ->setParameter('{{ min }}', $min)
//                ->setParameter('{{ max }}', $max)
//                ->addViolation();
//        }
//    }
//
//    /**
//     * @param ExecutionContextInterface $context
//     * @Assert\Callback()
//     */
//    public function summaryValidation(ExecutionContextInterface $context)
//    {
//        $v = $context->getValue()->getSummary();
//        $min = 5;
//        $max = 255;
//        if ((strlen($v) < $min || strlen($v) > $max) && $v !== null) {
//            $context
//                ->buildViolation('form.about.validation.summary')
//                ->setTranslationDomain('messages')
//                ->setParameter('{{ min }}', $min)
//                ->setParameter('{{ max }}', $max)
//                ->addViolation();
//        }
//    }
//
//    /**
//     * @param ExecutionContextInterface $context
//     * @Assert\Callback()
//     */
//    public function rangeActionValidation(ExecutionContextInterface $context)
//    {
//        $v = $context->getValue()->getRangeAction();
//        $min = 5;
//        $max = 255;
//        if ((strlen($v) < $min || strlen($v) > $max) && $v !== null) {
//            $context
//                ->buildViolation('form.about.validation.rangeAction')
//                ->setTranslationDomain('messages')
//                ->setParameter('{{ min }}', $min)
//                ->setParameter('{{ max }}', $max)
//                ->addViolation();
//        }
//    }
//
//    /**
//     * @param ExecutionContextInterface $context
//     * @Assert\Callback()
//     */
//    public function servicesValidation(ExecutionContextInterface $context)
//    {
//        $v = $context->getValue()->getServices();
//        $min = 50;
//        $max = 255;
//        if ((strlen($v) < $min || strlen($v) > $max) && $v !== null) {
//            $context
//                ->buildViolation('form.about.validation.services')
//                ->setTranslationDomain('messages')
//                ->setParameter('{{ min }}', $min)
//                ->setParameter('{{ max }}', $max)
//                ->addViolation();
//        }
//    }
}
