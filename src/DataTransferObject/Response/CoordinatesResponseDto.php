<?php

namespace App\DataTransferObject\Response;

class CoordinatesResponseDto extends AbstractResponseDto
{
    private float $lat;
    private float $lng;

    /**
     * @return float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     * @return CoordinatesResponseDto
     */
    public function setLat(float $lat): CoordinatesResponseDto
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * @return float
     */
    public function getLng(): float
    {
        return $this->lng;
    }

    /**
     * @param float $lng
     * @return CoordinatesResponseDto
     */
    public function setLng(float $lng): CoordinatesResponseDto
    {
        $this->lng = $lng;
        return $this;
    }
}