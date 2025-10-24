<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\WeatherService;
use App\Services\LlmService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        
        $this->app->singleton(WeatherService::class, function ($app) {
            $baseUrl = env('OPEN_METEO_URL', 'https://api.open-meteo.com/v1/forecast');
            return new WeatherService($baseUrl);
        });

        $this->app->singleton(LlmService::class, function ($app) {
            return new LlmService(
                endpoint: env('IA_API_URL', 'https://api.openai.com/v1/chat/completions'),
                apiKey: env('IA_API_KEY'),
                model: env('IA_MODEL', 'gpt-4o-mini')
            );
        });
    }

    public function boot(): void
    {
    }
}

