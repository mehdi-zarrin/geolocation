<?php

namespace App\Transformer;

use App\DataTransferObject\Response\ValidationErrorResponseDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;

class ValidationErrorTransformer
{
    /**
     * @param ValidationErrorResponseDto $dto
     * @return JsonResponse
     */
    public function transform(ValidationErrorResponseDto $dto): JsonResponse
    {
        $errors = [
            'validationErrors' => []
        ];

        /** @var ConstraintViolation $error */
        foreach ($dto->getErrors() as $error) {
            $error = [
                'message' => $error->getMessage(),
                'field' => $error->getPropertyPath()
            ];

            $errors['validationErrors'][] = $error;
        }

        return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
    }
}
