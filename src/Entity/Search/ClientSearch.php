<?php

namespace App\Entity\Search;


class ClientSearch
{
    /**
     * @var $siret int|null
     */
    private $siret;

    /**
     * @var $corporateName string|null
     */
    private $corporateName;

    /**
     * @var $companyName string|null
     */
    private $companyName;

    /**
     * @var string|null
     */
    private $legalForm;

    /**
     * @var $address string|null
     */
    private $address;

    /**
     * @var $postalCode string|null
     */
    private $postalCode;

    /**
     * @var $city string|null
     */
    private $city;

    /**
     * @var $location string|null
     */
    private $location;

    /**
     * @var $phone string|null
     */
    private $phone;

    /**
     * @var $fax string|null
     */
    private $fax;

    /**
     * @var $contact string|null
     */
    private $contact;

    /**
     * @var $webSite string|null
     */
    private $webSite;

    /**
     * @var $contract bool
     */
    private $contract;


    /**
     * @return int|null
     */
    public function getSiret(): ?int
    {
        return $this->siret;
    }

    /**
     * @param int|null $siret
     *
     * @return ClientSearch
     */
    public function setSiret(int $siret): ClientSearch
    {
        $this->siret = $siret;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCorporateName(): ?string
    {
        return $this->corporateName;
    }

    /**
     * @param string|null $corporateName
     *
     * @return ClientSearch
     */
    public function setCorporateName(string $corporateName): ClientSearch
    {
        $this->corporateName = $corporateName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * @param string|null $companyName
     *
     * @return ClientSearch
     */
    public function setCompanyName(string $companyName): ClientSearch
    {
        $this->companyName = $companyName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLegalForm(): ?string
    {
        return $this->legalForm;
    }

    /**
     * @param string|null $legalForm
     *
     * @return ClientSearch
     */
    public function setLegalForm(string $legalForm): ClientSearch
    {
        $this->legalForm = $legalForm;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     *
     * @return ClientSearch
     */
    public function setAddress(string $address): ClientSearch
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * @param string|null $postalCode
     *
     * @return ClientSearch
     */
    public function setPostalCode(string $postalCode): ClientSearch
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     *
     * @return ClientSearch
     */
    public function setCity(string $city): ClientSearch
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     *
     * @return ClientSearch
     */
    public function setLocation(string $location): ClientSearch
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string|null
     */
    public function getFax(): ?string
    {
        return $this->fax;
    }

    /**
     * @param string|null $fax
     */
    public function setFax(string $fax): void
    {
        $this->fax = $fax;
    }

    /**
     * @return string|null
     */
    public function getContact(): ?string
    {
        return $this->contact;
    }

    /**
     * @param string|null $contact
     */
    public function setContact(string $contact): void
    {
        $this->contact = $contact;
    }

    /**
     * @return string|null
     */
    public function getWebSite(): ?string
    {
        return $this->webSite;
    }

    /**
     * @param string|null $webSite
     */
    public function setWebSite(string $webSite): void
    {
        $this->webSite = $webSite;
    }

    /**
     * @return bool
     */
    public function getContract(): ?bool
    {
        return $this->contract;
    }

    /**
     * @param bool $contract
     *
     * @return ClientSearch
     */
    public function setContract(bool $contract): ClientSearch
    {
        $this->contract = $contract;

        return $this;
    }
}