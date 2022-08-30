<?php

namespace App\Service;

use App\Contracts\ServiceRequestInterface;
use App\Contracts\ServiceResponseInterface;

interface ServiceInterface
{
    /**
     * @param ServiceRequestInterface $serviceRequest
     * @return ServiceResponseInterface
     */
    public function handle(ServiceRequestInterface $serviceRequest): ServiceResponseInterface;
}
