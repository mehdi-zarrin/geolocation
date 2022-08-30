<?php

namespace App\Service\Geocode\Providers;

use App\Contracts\ServiceRequestInterface;
use App\Contracts\ServiceResponseInterface;
use App\DataTransferObject\Request\AddressRequestDto;
use App\DataTransferObject\Response\CoordinatesResponseDto;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HereMapsProvider implements GeocodeProviderInterface
{
    /**
     * @var string
     */
    private string $apiKey;

    /**
     * @var string
     */
    private string $host;

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
    ) {
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

        if (count($data['items']) === 0) {
            return null;
        }

        $firstItem = $data['items'][0];

        if ($firstItem['resultType'] !== 'houseNumber') {
            return null;
        }

        return (new CoordinatesResponseDto())
            ->setLat($firstItem['position']['lat'])
            ->setLng($firstItem['position']['lng']);
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
                'qq' => sprintf(
                    "country=%s;city=%s;street=%s;postalCode=%s",
                    $serviceRequest->getCountryCode(),
                    $serviceRequest->getCity(),
                    $serviceRequest->getStreet(),
                    $serviceRequest->getPostcode()
                ),
                'apiKey' => $this->apiKey
            ]
        ];

        return $this->httpClient->request(
            'GET',
            $this->host . '/v1/geocode',
            $params
        )->toArray();
    }
}
