<?php

namespace App\Services;

use App\Enums\MessageRole;
use App\Models\Conversation;
use App\Models\Message;
use App\DTOs\WeatherQueryDTO;
use Illuminate\Support\Facades\Log;

class ChatService
{
    public function __construct(
        private readonly WeatherService $weather,
        private readonly LlmService $llm
    ) {}

    public function handleUserMessage(Conversation $conversation, string $text, ?array $location = null): Message
    {
        $userMsg = $conversation->messages()->create([
            'role' => MessageRole::User,
            'content' => $text,
        ]);

        $city = $this->extractCityFromMessage($text);

        $weatherData = null;
        if ($this->isWeatherQuestion($text) && $city) {
            $dto = new WeatherQueryDTO(city: $city);
            $weatherData = $this->weather->getWeather($dto);
        }

        $messages = $this->buildPrompt($conversation, $weatherData);

        $ai = $this->llm->chat($messages);

        $assistantMsg = $conversation->messages()->create([
            'role' => MessageRole::Assistant,
            'content' => $ai['error']
                ? '⚠️ Error procesando tu solicitud. Intenta de nuevo más tarde.'
                : $ai['content'],
            'meta' => ['weather' => $weatherData, 'llm' => $ai],
        ]);

        return $assistantMsg;
    }

    private function isWeatherQuestion(string $text): bool
    {
        $t = mb_strtolower($text);
        return str_contains($t, 'clima')
            || str_contains($t, 'tiempo')
            || str_contains($t, 'temperatura')
            || str_contains($t, 'lloverá')
            || str_contains($t, 'paraguas');
    }

    private function extractCityFromMessage(string $text): ?string
    {
        if (preg_match('/en\s+([A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)/u', $text, $matches)) {
            return ucfirst(trim($matches[1]));
        }

        $commonCities = ['Bogotá', 'Madrid', 'Berlín', 'Buenos Aires', 'México', 'Tokio', 'Lima', 'Quito', 'Santiago', 'Medellín'];
        foreach ($commonCities as $city) {
            if (stripos($text, $city) !== false) {
                return $city;
            }
        }

        return 'Bogotá';
    }


    private function buildPrompt(Conversation $conversation, ?array $weather): array
    {
        $system = [
            'role' => 'system',
            'content' => <<<PROMPT
Eres **MeteoBot**, un asistente meteorológico que responde en español.
- Personalidad: amable, precisa y profesional ☀️
- Objetivo: informar el clima actual o pronóstico usando datos reales de Open-Meteo.
- Si no hay datos exactos, dilo de forma clara y sugiere cómo obtenerlos.
- Usa **negritas** para valores importantes y máximo 2 emojis.
- Siempre indica el nombre de la ciudad si lo conoces.
- Ejemplo de respuesta:
  "En **Madrid**, la temperatura actual es de **18°C** con cielo despejado ☀️ y viento de **5 km/h**."
PROMPT
        ];

        // Historial reciente de la conversación (últimos 8 mensajes)
        $history = $conversation->messages()->latest()->take(8)->get()
            ->reverse()
            ->map(fn($m) => ['role' => $m->role->value, 'content' => $m->content])
            ->values()
            ->all();

        if ($weather && !($weather['error'] ?? false)) {
            $systemWeather = [
                'role' => 'system',
                'content' => 'Datos meteorológicos actuales: ' . json_encode($weather['data'], JSON_UNESCAPED_UNICODE)
            ];
            return [$system, $systemWeather, ...$history];
        }

        return [$system, ...$history];
    }
}