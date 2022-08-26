<?php

namespace App\Service\Geocode\Providers;

use App\Contracts\ServiceRequestInterface;
use App\Contracts\ServiceResponseInterface;
use App\DataTransferObject\Request\AddressRequestDto;
use App\DataTransferObject\Response\CoordinatesResponseDto;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GoogleMapsProvider implements GeocodeProviderInterface
{
    /**
     * @var string
     */
    protected string $apiKey;

    /**
     * @var string
     */
    protected string $host;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @param string $apiKey
     * @param string $host
     * @param HttpClientInterface $httpClient
     */
    public function __construct(
        string $apiKey,
        string $host,
        HttpClientInterface $httpClient
    )
    {
        $this->apiKey = $apiKey;
        $this->host = $host;
        $this->httpClient = $httpClient;
    }

    /**
     * @inheritDoc
     */
    public function process(ServiceRequestInterface $serviceRequest): ?ServiceResponseInterface
    {
        try {

            $data = $this->getData($serviceRequest);

        } catch (\Exception $e) {

            return null;
        }

        if (count($data['results']) === 0) {
            return null;
        }

        $firstResult = $data['results'][0];

        if ($firstResult['geometry']['location_type'] !== 'ROOFTOP') {
            return null;
        }

        return (new CoordinatesResponseDto())
            ->setLat($firstResult['geometry']['location']['lat'])
            ->setLng($firstResult['geometry']['location']['lng']);
    }

    /**
     * @param AddressRequestDto $serviceRequest
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    protected function getData(ServiceRequestInterface $serviceRequest): array
    {
        $params = [
            'query' => [
                'address' => $serviceRequest->getStreet(),
                'components' => sprintf(
                    "country:%s|locality:%s|postal_code:%s",
                    $serviceRequest->getCountryCode(),
                    $serviceRequest->getCity(),
                    $serviceRequest->getPostcode()
                ),
                'key' => $this->apiKey
            ]
        ];

        return $this->httpClient->request(
            'GET',
            $this->host . '/maps/api/geocode/json',
            $params
        )->toArray();
    }
}