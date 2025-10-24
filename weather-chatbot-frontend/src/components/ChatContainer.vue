<template>
  <div class="flex flex-col h-[500px] bg-white rounded-2xl shadow-lg">
    <!-- Lista de mensajes -->
    <div ref="chatContainer" class="flex-1 overflow-y-auto p-4 bg-gray-50 rounded-t-2xl">
      <div
        v-for="(msg, index) in messages"
        :key="index"
        class="mb-3 flex"
        :class="msg.role === 'user' ? 'justify-end' : 'justify-start'"
      >
        <div
          :class="[ 
            msg.role === 'user'
              ? 'bg-blue-600 text-white rounded-br-none'
              : 'bg-gray-200 text-gray-800 rounded-bl-none',
            'p-3 rounded-lg w-fit max-w-[80%] shadow-sm whitespace-pre-wrap'
          ]"
        >
          <p v-html="msg.displayText"></p>
        </div>
      </div>

      <!-- Loader -->
      <div v-if="loading" class="flex justify-center py-4">
        <div class="flex items-center gap-2 text-gray-500">
          <svg
            class="animate-spin h-5 w-5 text-blue-500"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
          >
            <circle
              class="opacity-25"
              cx="12"
              cy="12"
              r="10"
              stroke="currentColor"
              stroke-width="4"
            ></circle>
            <path
              class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
            ></path>
          </svg>
          <span>Procesando tu mensaje...</span>
        </div>
      </div>
    </div>

    <!-- Input del usuario -->
    <div class="border-t p-3 flex gap-2 bg-white rounded-b-2xl">
      <input
        v-model="userInput"
        @keyup.enter="sendMessage"
        type="text"
        placeholder="Escribe tu pregunta..."
        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
      />
      <button
        @click="sendMessage"
        :disabled="loading || !userInput"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg disabled:opacity-50"
      >
        Enviar
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, nextTick, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
  chat: {
    type: Object,
    default: () => ({ id: null, messages: [] }),
  },
})

const emit = defineEmits(['exit'])

const messages = ref(props.chat?.messages || [])
const userInput = ref('')
const loading = ref(false)
const chatContainer = ref(null)
const API_URL = `${import.meta.env.VITE_API_URL}/chat/send`

// 🔄 Sincroniza mensajes si cambia la conversación seleccionada
watch(
  () => props.chat,
  (newChat) => {
    messages.value = (newChat?.messages || []).map((m) => ({
      ...m,
      displayText: m.content,
    }))
  },
  { deep: true, immediate: true }
)

const sendMessage = async () => {
  if (!userInput.value.trim()) return

  const text = userInput.value
  userInput.value = ''
  messages.value.push({ role: 'user', content: text, displayText: text })
  loading.value = true

  try {
    const { data } = await axios.post(API_URL, {
      text,
      conversation_id: props.chat?.id,
    })

    const content =
      data?.message?.content ||
      '⚠️ No se pudo generar una respuesta. Intenta nuevamente.'

    const assistantMsg = { role: 'assistant', content, displayText: '' }
    messages.value.push(assistantMsg)
    await typeEffect(assistantMsg)
  } catch (error) {
    messages.value.push({
      role: 'assistant',
      content: '❌ Error al procesar tu solicitud. Verifica el servidor.',
      displayText: '❌ Error al procesar tu solicitud. Verifica el servidor.',
    })
  } finally {
    loading.value = false
    await nextTick()
    chatContainer.value.scrollTop = chatContainer.value.scrollHeight
  }
}

const typeEffect = async (msg) => {
  const text = msg.content
  msg.displayText = ''
  for (let i = 0; i < text.length; i++) {
    msg.displayText += text[i]
    await nextTick()
    chatContainer.value.scrollTop = chatContainer.value.scrollHeight
    await new Promise((r) => setTimeout(r, 20))
  }
}
</script>
