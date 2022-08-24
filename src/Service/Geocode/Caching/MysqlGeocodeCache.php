<?php

namespace App\Service\Geocode\Caching;

use App\Contracts\ServiceRequestInterface;
use App\Contracts\ServiceResponseInterface;
use App\DataTransferObject\Request\AddressRequestDto;
use App\DataTransferObject\Response\CoordinatesResponseDto;
use App\Repository\ResolvedAddressRepository;

class MysqlGeocodeCache implements GeocodeCacheInterface
{
    /**
     * @var ResolvedAddressRepository
     */
    private ResolvedAddressRepository $resolvedAddressRepository;

    public function __construct(ResolvedAddressRepository $resolvedAddressRepository)
    {
        $this->resolvedAddressRepository = $resolvedAddressRepository;
    }

    /**
     * @inheritDoc
     * @param AddressRequestDto $addressRequestDto
     */
    public function get(ServiceRequestInterface $addressRequestDto): ?CoordinatesResponseDto
    {
        $resolvedAddressEntity = $this->resolvedAddressRepository->getByAddress($addressRequestDto);

        if (
            $resolvedAddressEntity &&
            $resolvedAddressEntity->getLat() &&
            $resolvedAddressEntity->getLng()
        ) {
            return (new CoordinatesResponseDto())
                ->setLng($resolvedAddressEntity->getLng())
                ->setLat($resolvedAddressEntity->getLat());
        }

        return null;
    }

    /**
     * @param AddressRequestDto $addressRequestDto
     * @param ?CoordinatesResponseDto $coordinatesResponseDto
     */
    public function set(ServiceRequestInterface $addressRequestDto, ?ServiceResponseInterface $coordinatesResponseDto)
    {
        $this->resolvedAddressRepository->saveResolvedAddress($addressRequestDto, $coordinatesResponseDto);
    }
}