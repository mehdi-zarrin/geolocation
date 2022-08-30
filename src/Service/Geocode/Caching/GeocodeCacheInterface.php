<?php

namespace App\Service\Geocode\Caching;

use App\Contracts\ServiceRequestInterface;
use App\Contracts\ServiceResponseInterface;

interface GeocodeCacheInterface
{
    /**
     * @param ServiceRequestInterface $addressRequestDto
     * @return ServiceResponseInterface|null
     */
    public function get(ServiceRequestInterface $addressRequestDto): ?ServiceResponseInterface;

    /**
     * @param ServiceRequestInterface $serviceRequest
     * @param ServiceResponseInterface|null $serviceResponse
     * @return void
     */
    public function set(ServiceRequestInterface $serviceRequest, ?ServiceResponseInterface $serviceResponse);
}
