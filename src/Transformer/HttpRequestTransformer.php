<?php

namespace App\Transformer;

use App\Contracts\ServiceRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class HttpRequestTransformer
{
    private ObjectNormalizer $objectNormalizer;

    /**
     * @param ObjectNormalizer $objectNormalizer
     */
    public function __construct(ObjectNormalizer $objectNormalizer)
    {
        $this->objectNormalizer = $objectNormalizer;
    }

    /**
     * @param Request $request
     * @param ServiceRequestInterface $dto
     * @return ServiceRequestInterface
     */
    public function transform(Request $request, ServiceRequestInterface $dto): ServiceRequestInterface
    {
        $data = array_merge(
            json_decode($request->getContent(), true) ?? [],
            $request->query->all()
        );

        // TODO: in PHP >= 8 we could do $dto::class instead
        return $this->objectNormalizer->denormalize($data, get_class($dto));
    }
}
