# ☁️ Weather Chatbot Backend (Laravel 11 + OpenAI + Open-Meteo)

Este repositorio contiene el **backend** del proyecto **Weather Chatbot**, desarrollado con **Laravel 11**, que permite conversar con una inteligencia artificial sobre el clima de distintas ciudades del mundo 🌎.  
El sistema combina **procesamiento de lenguaje natural (OpenAI)** con **datos meteorológicos en tiempo real (Open-Meteo)**, ofreciendo respuestas naturales, personalizadas y almacenando el historial de conversación.

---

## 🧠 Descripción general

El chatbot identifica cuando una pregunta requiere información climática y automáticamente consulta la API de **Open-Meteo**, usando coordenadas geográficas obtenidas desde la API de geocodificación de la misma plataforma.  
Luego genera una respuesta natural y contextual con ayuda del modelo **OpenAI GPT-4o-mini**.

Flujo resumido:
1. El usuario envía un mensaje (por ejemplo: “¿Cómo está el clima en Madrid?”).
2. El backend detecta si se trata de una consulta meteorológica.
3. Se consulta Open-Meteo → se obtiene temperatura, viento, etc.
4. Se construye un *prompt* estructurado y se envía a OpenAI.
5. La respuesta se guarda junto con la conversación en la base de datos.

---

## ⚙️ Instalación y configuración

### 1️⃣ Clonar el proyecto
```bash
git clone https://github.com/juanpabloperez98/weather-chatbot-fullstack.git
cd weather-chatbot-fullstack/weather-chatbot-11


2️⃣ Instalar dependencias
composer install

3️⃣ Crear el archivo de entorno
cp .env.example .env


🧩 Importante:
Por políticas de seguridad, este archivo no incluye una clave activa de OpenAI.
Para ejecutar el proyecto localmente, debes reemplazar la variable IA_API_KEY en tu archivo .env con la siguiente clave de prueba local:

La variable que debes modificar es:

IA_API_KEY=[tu clave de IA de prueba]


👉 En tu entorno local puedes usar esta clave de prueba educativa:

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


⚠️ Esta clave es solo para uso local o educativo y no debe subirse a GitHub.
(GitHub puede bloquear commits que incluyan esta cadena en texto plano.)

4️⃣ Generar la clave de aplicación
php artisan key:generate

5️⃣ Configurar base de datos en .env

Ejemplo:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=weather_chatbot
DB_USERNAME=root
DB_PASSWORD=

6️⃣ Ejecutar migraciones
php artisan migrate

7️⃣ Iniciar el servidor local
php artisan serve


Tu API quedará disponible en:
👉 http://127.0.0.1:8000/api

🌐 Variables de entorno clave
APP_URL=http://127.0.0.1:8000

OPEN_METEO_URL=https://api.open-meteo.com/v1/forecast
GEOCODING_URL=https://geocoding-api.open-meteo.com/v1/search

IA_API_URL=https://api.openai.com/v1/chat/completions
IA_API_KEY=         # <-- Reemplaza manualmente con tu clave local
IA_MODEL=gpt-4o-mini

🔍 Endpoints principales
Método	Endpoint	Descripción
POST	/api/chat/send	Envía un mensaje y obtiene respuesta del chatbot
GET	/api/chat/{id}	Recupera el historial de una conversación específica
GET	/api/chat/history	Lista todas las conversaciones registradas
🧠 Prompt del modelo

El prompt está cuidadosamente estructurado siguiendo buenas prácticas de prompt engineering:

Eres MeteoBot. Responde en español, breve y claro.
- Personalidad: amable y profesional.
- Objetivo: responder preguntas del clima usando datos de Open-Meteo si existen.
- Si no tienes datos exactos, indícalo y sugiere cómo obtenerlos.
- Usa **negritas** para valores importantes y máximo 2 emojis.

🧪 Prueba rápida con Postman

Endpoint:

POST http://127.0.0.1:8000/api/chat/send


Body (JSON):

{
  "text": "¿Cómo está el clima en Bogotá?"
}


Respuesta esperada:

{
  "message": {
    "role": "assistant",
    "content": "En **Bogotá**, la temperatura actual es de **13.7°C**, con cielo nublado 🌥️ y vientos suaves."
  }
}

🧩 Estructura del proyecto
app/
 ├── DTOs/
 │   └── WeatherQueryDTO.php
 ├── Enums/
 │   └── MessageRole.php
 ├── Http/
 │   └── Controllers/
 │       └── Api/ChatController.php
 ├── Models/
 │   ├── Conversation.php
 │   └── Message.php
 ├── Services/
 │   ├── ChatService.php
 │   ├── WeatherService.php
 │   └── LlmService.php
 └── Providers/
     └── AppServiceProvider.php

routes/
 ├── api.php
 └── web.php

database/
 ├── migrations/
 │   ├── create_conversations_table.php
 │   └── create_messages_table.php
 └── seeders/

🧱 Principios aplicados

✅ SOLID + Clean Architecture
✅ Inyección de dependencias (AppServiceProvider)
✅ DTOs y Enums para claridad y validación
✅ Manejo robusto de errores (try/catch)
✅ Servicios desacoplados (ChatService, WeatherService, LlmService)
✅ Prompts estructurados y seguros (anti-prompt-injection)
✅ Eloquent ORM optimizado
✅ Mensajes y respuestas 100% en español
✅ Commits atómicos y descriptivos (feat, fix, chore, refactor, etc.)

💬 Ejemplo de flujo completo

1️⃣ El usuario inicia un nuevo chat.
2️⃣ Envía: “¿Necesitaré paraguas en Berlín mañana?”
3️⃣ El sistema detecta una pregunta sobre clima.
4️⃣ Se consulta Open-Meteo para obtener datos reales.
5️⃣ OpenAI genera la respuesta en lenguaje natural.
6️⃣ La conversación se guarda en base de datos y puede consultarse luego.

Respuesta generada:

☔ En Berlín, se espera lluvia ligera mañana. ¡Te recomiendo llevar paraguas!

🔒 Seguridad

No se suben archivos sensibles (.env, vendor, node_modules)

Las claves API se configuran localmente y no se exponen en commits

Manejo de excepciones con mensajes seguros y en español

Logs controlados y sin dump() ni dd()

🧑‍💻 Autor

Juan Pablo Pérez Santos
Desarrollador Fullstack
📧 juanpabloperezdevelopment@gmail.com

💼 https://www.linkedin.com/in/juan-pablo-perez-santos-b68a30189/

🧾 Nota final:
Este backend fue diseñado siguiendo los lineamientos de la prueba técnica, con una arquitectura clara, desacoplada y documentada, asegurando una experiencia fluida tanto para el usuario como para el evaluador.