<?php

namespace App\Transformer;

use App\Contracts\ServiceResponseInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class HttpResponseTransformer
{
    private ObjectNormalizer $objectNormalizer;
    private const RESPONSE_WRAPPER_STRING = 'data';

    /**
     * @param ObjectNormalizer $objectNormalizer
     */
    public function __construct(ObjectNormalizer $objectNormalizer)
    {
        $this->objectNormalizer = $objectNormalizer;
    }

    /**
     * @param ServiceResponseInterface $responseDto
     * @return Response
     */
    public function transform(ServiceResponseInterface $responseDto): Response
    {
        // TODO: in PHP >= 8 use named arguments to bypass the second argument
        $normalizedData = $this->objectNormalizer->normalize(
            $responseDto,
            null,
            [AbstractNormalizer::IGNORED_ATTRIBUTES => ['statusCode']]
        );

        $response = [
            static::RESPONSE_WRAPPER_STRING => $normalizedData
        ];

        return new JsonResponse($response, $responseDto->getStatusCode());
    }
}
