<?php

namespace App\Service\Geocode\Providers;

use App\Contracts\ServiceRequestInterface;
use App\Contracts\ServiceResponseInterface;
use App\DataTransferObject\Response\EmptyResponseDto;
use App\Service\Geocode\Caching\GeocodeCacheInterface;

class CacheProvider implements GeocodeProviderInterface
{
    private GeocodeCacheInterface $geocodeCache;

    /**
     * @param GeocodeCacheInterface $geocodeCache
     */
    public function __construct(GeocodeCacheInterface $geocodeCache)
    {
        $this->geocodeCache = $geocodeCache;
    }

    /**
     * @inheritDoc
     */
    public function process(ServiceRequestInterface $serviceRequest): ?ServiceResponseInterface
    {
        $cachedResult = $this->geocodeCache->get($serviceRequest);
        if ($cachedResult) {
            return $cachedResult;
        }

        return null;
    }
}
