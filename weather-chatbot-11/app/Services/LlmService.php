<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class LlmService
{
    public function __construct(
        private readonly string $endpoint,
        private readonly string $apiKey,
        private readonly string $model
    ) {}

    public function chat(array $messages, array $options = []): array
    {
        $systemPrompt = [
            'role' => 'system',
            'content' => <<<EOT
Eres un asistente meteorológico llamado **ClimaBot 🌦️**.
Tu tarea es responder preguntas sobre el clima en español, de forma clara, concisa y amable.

**Objetivos:**
- Proporcionar información sobre el clima actual o pronósticos.
- Usar el sistema métrico (°C, km/h).
- Incluir emojis de clima cuando corresponda (☀️🌧️🌤️❄️).

**Reglas:**
- Si tienes acceso a datos del clima (vía API), intégralos en tu respuesta.
- Si no tienes datos exactos, di: "No tengo datos precisos en este momento."
- Nunca inventes datos meteorológicos.
- Responde siempre en español, incluso si la pregunta está en otro idioma.

**Formato:**
- Usa Markdown: **negritas** para resaltar valores importantes.
- Usa frases cortas (máximo 3).
- No incluyas enlaces ni código.

Ejemplo:
Usuario: ¿Cómo está el clima en Madrid?
Asistente: En **Madrid**, la temperatura actual es de **19°C**, con cielo despejado ☀️.
EOT
        ];

        $finalMessages = array_merge([$systemPrompt], $messages);

        $payload = [
            'model' => $this->model,
            'messages' => $finalMessages,
            'temperature' => $options['temperature'] ?? 0.4,
            'max_tokens' => $options['max_tokens'] ?? 300,
        ];

        try {
            $res = Http::withToken($this->apiKey)
                ->timeout(15)
                ->post($this->endpoint, $payload);

            if (!$res->ok()) {
                return [
                    'error' => true,
                    'message' => 'Error en el proveedor IA',
                    'status' => $res->status(),
                    'body' => $res->json(),
                ];
            }

            return [
                'error' => false,
                'content' => $res->json()['choices'][0]['message']['content'] ?? '',
            ];
        } catch (Throwable $e) {
            return [
                'error' => true,
                'message' => 'Error de red con IA',
                'exception' => $e->getMessage(),
            ];
        }
    }
}

