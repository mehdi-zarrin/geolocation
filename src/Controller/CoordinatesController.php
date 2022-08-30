<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObject\Request\AddressRequestDto;
use App\DataTransferObject\Response\ValidationErrorResponseDto;
use App\Service\Geocode\GeocoderInterface;
use App\Transformer\HttpRequestTransformer;
use App\Transformer\HttpResponseTransformer;
use App\Transformer\ValidationErrorTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CoordinatesController extends AbstractController
{
    private GeocoderInterface $geocoder;
    private HttpRequestTransformer $httpRequestTransformer;
    private ValidatorInterface $validator;
    private ValidationErrorTransformer $validationErrorTransformer;
    private HttpResponseTransformer $httpResponseTransformer;

    public function __construct(
        GeocoderInterface $geocoder,
        HttpRequestTransformer $httpRequestTransformer,
        ValidatorInterface $validator,
        ValidationErrorTransformer $validationErrorTransformer,
        HttpResponseTransformer $httpResponseTransformer
    ) {
        $this->geocoder = $geocoder;
        $this->httpRequestTransformer = $httpRequestTransformer;
        $this->validator = $validator;
        $this->validationErrorTransformer = $validationErrorTransformer;
        $this->httpResponseTransformer = $httpResponseTransformer;
    }

    /**
     * @Route(path="/coordinates", name="geocode")
     * @param Request $request
     * @return Response
     */
    public function geocodeAction(Request $request): Response
    {
        $serviceRequest = $this->httpRequestTransformer->transform($request, new AddressRequestDto());
        $validationErrors = $this->validator->validate($serviceRequest);

        if ($validationErrors->count() > 0) {
            return $this->validationErrorTransformer->transform(
                (new ValidationErrorResponseDto())->setErrors($validationErrors)
            );
        }

        return $this->httpResponseTransformer->transform(
            $this->geocoder->handle($serviceRequest)
        );
    }
}
