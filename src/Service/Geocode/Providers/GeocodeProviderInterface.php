<?php

namespace App\Service\Geocode\Providers;

use App\Contracts\ServiceRequestInterface;
use App\Contracts\ServiceResponseInterface;

interface GeocodeProviderInterface
{
    /**
     * @param ServiceRequestInterface $serviceRequest
     * @return ServiceResponseInterface
     */
    public function process(ServiceRequestInterface $serviceRequest): ?ServiceResponseInterface;
}