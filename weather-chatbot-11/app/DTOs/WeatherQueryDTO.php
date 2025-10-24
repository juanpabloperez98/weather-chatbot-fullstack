<?php

namespace App\DTOs;

class WeatherQueryDTO
{
    public function __construct(
        public readonly ?float $latitude = null,
        public readonly ?float $longitude = null,
        public readonly ?string $city = null,
    ) {}
}
