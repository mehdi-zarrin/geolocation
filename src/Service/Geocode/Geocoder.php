<?php

declare(strict_types=1);

namespace App\Service\Geocode;

use App\Contracts\ServiceRequestInterface;
use App\Contracts\ServiceResponseInterface;
use App\DataTransferObject\Response\EmptyResponseDto;
use App\Service\Geocode\Caching\GeocodeCacheInterface;
use App\Service\Geocode\Providers\CacheProvider;
use App\Service\Geocode\Providers\GeocodeProviderInterface;

class Geocoder implements GeocoderInterface
{
    /**
     * @var GeocodeProviderInterface[]
     */
    private array $providers;

    /**
     * @var GeocodeCacheInterface
     */
    private GeocodeCacheInterface $geocodeCache;

    /**
     * @param GeocodeCacheInterface $geocodeCache
     * @param GeocodeProviderInterface ...$providers
     */
    public function __construct(GeocodeCacheInterface $geocodeCache, GeocodeProviderInterface ...$providers)
    {
        $this->providers = $providers;
        $this->geocodeCache = $geocodeCache;
    }

    /**
     * @param ServiceRequestInterface $serviceRequest
     * @return ServiceResponseInterface
     */
    public function handle(ServiceRequestInterface $serviceRequest): ServiceResponseInterface
    {
        $response = new EmptyResponseDto();
        $currentProvider = null;

        foreach ($this->providers as $provider) {

            $currentProvider = $provider;

            $response = $provider->process($serviceRequest);

            if ($response) {
                break;
            }
        }

        if (!$this->alreadyInCache($currentProvider)) {
            $this->geocodeCache->set($serviceRequest, $response);
        }

        return $response ?? new EmptyResponseDto();
    }

    /**
     * @param GeocodeProviderInterface|null $currentProvider
     * @return bool
     */
    protected function alreadyInCache(?GeocodeProviderInterface $currentProvider): bool
    {
        return $currentProvider instanceof CacheProvider;
    }
}
