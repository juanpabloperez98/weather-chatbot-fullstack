<?php

namespace App\Services;

use App\DTOs\WeatherQueryDTO;
use Illuminate\Support\Facades\Http;
use Throwable;

class WeatherService
{
    private string $geocodingUrl;

    public function __construct(
        private readonly string $baseUrl
    ) {
        $this->geocodingUrl = env('GEOCODING_URL', 'https://geocoding-api.open-meteo.com/v1/search');
    }

    public function getWeather(WeatherQueryDTO $dto): array
    {
        try {
            $geoRes = Http::timeout(8)
                ->retry(2, 250)
                ->get($this->geocodingUrl, [
                    'name' => $dto->city,
                    'count' => 1,
                ]);

            if (!$geoRes->ok() || empty($geoRes['results'])) {
                return [
                    'error' => true,
                    'message' => 'No se pudo obtener la ubicación'
                ];
            }

            $coords = $geoRes['results'][0];
            $latitude = $coords['latitude'];
            $longitude = $coords['longitude'];

            $query = [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'current_weather' => true,
                'timezone' => 'auto',
            ];

            $res = Http::timeout(8)
                ->retry(2, 250)
                ->get($this->baseUrl, $query);

            if (!$res->ok()) {
                return [
                    'error' => true,
                    'message' => 'No se pudo obtener el clima'
                ];
            }

            return [
                'error' => false,
                'data' => [
                    'city' => $dto->city,
                    'coordinates' => [
                        'lat' => $latitude,
                        'lng' => $longitude,
                    ],
                    'weather' => $res->json(),
                ],
            ];
        } catch (Throwable $e) {
            return [
                'error' => true,
                'message' => 'Error al consultar el clima',
                'exception' => $e->getMessage(),
            ];
        }
    }
}