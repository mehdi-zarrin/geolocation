<?php

namespace App\DataTransferObject\Response;

use App\Contracts\ServiceResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class AbstractResponseDto implements ServiceResponseInterface
{
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return Response::HTTP_OK;
    }

}