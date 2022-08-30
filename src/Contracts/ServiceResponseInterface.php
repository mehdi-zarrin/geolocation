<?php

namespace App\Contracts;

use Symfony\Component\Serializer\Annotation\Ignore;

interface ServiceResponseInterface
{
    /**
     * @return int
     */
    public function getStatusCode(): int;
}
