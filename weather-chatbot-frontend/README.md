# 💬 Weather Chatbot Frontend (Vue 3 + Tailwind CSS)  

Este repositorio contiene el **frontend** del proyecto **Weather Chatbot**, desarrollado con **Vue.js 3 (Composition API)** y **Tailwind CSS**, que ofrece una interfaz moderna y limpia para interactuar con el asistente inteligente de clima 🌦️.  

El sistema permite al usuario escribir preguntas sobre el clima, obtener respuestas generadas por IA, visualizar el historial de conversaciones y crear nuevos chats de manera fluida, con una experiencia visual tipo WhatsApp.  

---

## ⚙️ Requisitos previos  
Antes de iniciar, asegúrate de tener instalado:  
- [Node.js 20+](https://nodejs.org/)  
- [npm 10+](https://www.npmjs.com/)  
- [Git](https://git-scm.com/)  

---

## 🚀 Instalación y ejecución  

### 1️⃣ Clonar el proyecto  
```bash
git clone https://github.com/juanpabloperez98/weather-chatbot-fullstack.git
cd weather-chatbot-fullstack/weather-chatbot-frontend


2️⃣ Instalar dependencias
npm install

3️⃣ Configurar variables de entorno

Crea un archivo .env en la raíz del proyecto con la siguiente variable:

VITE_API_URL=http://127.0.0.1:8000/api


Esta variable apunta al backend en Laravel que expone los endpoints del chatbot.
Si el backend corre en otra dirección o puerto, actualízalo aquí.

4️⃣ Iniciar el entorno de desarrollo
npm run dev


La aplicación se ejecutará por defecto en 👉 http://localhost:5173

🧩 Estructura del proyecto
src/
 ├── assets/
 │   ├── base.css
 │   ├── main.css
 │   └── logo.svg
 ├── components/
 │   ├── ChatContainer.vue
 │   ├── ChatHistory.vue
 │   └── WeatherIcon.vue (opcional)
 ├── App.vue
 └── main.js


Cada componente cumple una función específica:

ChatContainer.vue: Componente principal del chat. Maneja los mensajes, el input del usuario y la interacción con la API del backend.

ChatHistory.vue: Muestra la lista de conversaciones previas, permitiendo al usuario seleccionar o iniciar un nuevo chat.

App.vue: Estructura principal de la interfaz que integra ambos componentes y coordina su comunicación.

🎨 Estilos y diseño

El proyecto utiliza Tailwind CSS para mantener un diseño limpio y responsivo.
Los elementos visuales siguen una estética de chat minimalista: burbujas diferenciadas, sombreado suave y tipografía legible.

Para personalizar colores o fuentes, edita el archivo:

tailwind.config.js

🔗 Comunicación con el backend

Toda la comunicación con la API se realiza mediante Axios.
El endpoint principal utilizado es:

POST {VITE_API_URL}/chat/send


Ejemplo de request:

{
  "text": "¿Cómo está el clima en Bogotá?"
}


Ejemplo de respuesta:

{
  "message": {
    "role": "assistant",
    "content": "En **Bogotá**, la temperatura actual es de **13.7°C**, con cielo nublado 🌥️."
  }
}


Los mensajes del usuario y del asistente se renderizan dinámicamente con efecto de tipeo (typewriter effect) para dar una experiencia más natural.

🧠 Características principales

✅ Chat responsivo con visualización tipo mensajería.
✅ Efecto de escritura progresiva en respuestas.
✅ Manejo de errores con mensajes claros para el usuario.
✅ Historial de conversaciones dinámico.
✅ Feedback visual mientras la IA procesa la respuesta.
✅ Comunicación desacoplada con el backend mediante variables de entorno (VITE_API_URL).
✅ Código limpio, siguiendo principios SOLID y convenciones de Vue 3.

⚡ Scripts disponibles
Comando	Descripción
npm run dev	Inicia el servidor de desarrollo con hot reload
npm run build	Genera una versión optimizada para producción
npm run preview	Sirve la aplicación compilada localmente
🧱 Buenas prácticas implementadas

Componentes organizados por funcionalidad.

Uso exclusivo de clases utilitarias de Tailwind (sin CSS adicional innecesario).

Eliminación de logs (console.log) y comentarios residuales antes del commit.

Variables y métodos nombrados en inglés (camelCase / PascalCase).

Textos visibles 100% en español para el usuario final.

Cumplimiento con las convenciones del backend y consistencia de diseño.

💬 Ejemplo de flujo completo

1️⃣ El usuario abre la app y ve su historial de chats previos.
2️⃣ Escribe: “¿Cómo está el clima en Madrid?”.
3️⃣ El frontend envía la petición al backend vía axios.post().
4️⃣ El backend (Laravel) analiza la intención, obtiene los datos de Open-Meteo y devuelve la respuesta generada por IA.
5️⃣ El frontend muestra el mensaje con animación, guarda el nuevo chat en la lista y actualiza el historial.

Ejemplo visual:

👤 Usuario: ¿Cómo está el clima en Berlin?
🤖 MeteoBot: En **Berlín**, la temperatura actual es de **11.8°C**, con cielo nublado 🌥️. El viento sopla a **14.6 km/h**.

🧑‍💻 Autor

Juan Pablo Pérez Santos
Desarrollador Fullstack
📧 juanpabloperezdevelopment@gmail.com

💼 https://www.linkedin.com/in/juan-pablo-perez-santos-b68a30189/

🧾 Nota final

Este frontend fue diseñado siguiendo los lineamientos de la prueba técnica, priorizando una UI intuitiva, un código limpio y una integración fluida con el backend Laravel.