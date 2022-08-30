<?php

namespace App\DataTransferObject\Response;

use App\Contracts\ServiceResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationErrorResponseDto implements ServiceResponseInterface
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $errors;

    /**
     * @return ConstraintViolationListInterface
     */
    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }

    /**
     * @param ConstraintViolationListInterface $errors
     * @return ValidationErrorResponseDto
     */
    public function setErrors(ConstraintViolationListInterface $errors): ValidationErrorResponseDto
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
