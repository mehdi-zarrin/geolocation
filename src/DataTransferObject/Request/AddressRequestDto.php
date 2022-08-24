<?php

namespace App\DataTransferObject\Request;

use App\Contracts\ServiceRequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AddressRequestDto implements ServiceRequestInterface
{
    /**
     * @Assert\NotBlank()
     */
    private string $countryCode;

    /**
     * @Assert\NotBlank()
     */
    private string $city;

    /**
     * @Assert\NotBlank()
     */
    private string $street;

    /**
     * @Assert\NotBlank()
     */
    private string $postcode;

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     * @return AddressRequestDto
     */
    public function setCountryCode(string $countryCode): AddressRequestDto
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return AddressRequestDto
     */
    public function setCity(string $city): AddressRequestDto
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return AddressRequestDto
     */
    public function setStreet(string $street): AddressRequestDto
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     * @return AddressRequestDto
     */
    public function setPostcode(string $postcode): AddressRequestDto
    {
        $this->postcode = $postcode;
        return $this;
    }
}