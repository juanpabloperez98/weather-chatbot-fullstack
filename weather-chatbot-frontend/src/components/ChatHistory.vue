<template>
  <div class="p-4 bg-white rounded-2xl shadow-lg">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold text-gray-800">💬 Historial de Chats</h2>
      <button
        @click="startNewChat"
        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-sm transition"
      >
        ➕ Nuevo Chat
      </button>
    </div>

    <div v-if="loading" class="text-gray-500 italic text-center py-6">
      Cargando historial...
    </div>

    <div v-else-if="conversations.length === 0" class="text-gray-500 text-center py-6">
      No hay conversaciones aún.
    </div>

    <ul v-else class="space-y-2 max-h-[400px] overflow-y-auto">
      <li
        v-for="chat in conversations"
        :key="chat.id"
        @click="fetchConversation(chat.id)"
        class="p-3 rounded-lg cursor-pointer border border-gray-200 hover:bg-blue-50 transition"
      >
        <div class="flex justify-between items-center">
          <h3 class="font-medium text-gray-800">{{ chat.title }}</h3>
          <span class="text-xs text-gray-500">{{ formatDate(chat.updated_at) }}</span>
        </div>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const emit = defineEmits(['select'])
const conversations = ref([])
const loading = ref(true)
const API_BASE_URL = import.meta.env.VITE_API_URL

const fetchHistory = async () => {
  try {
    const { data } = await axios.get(`${API_BASE_URL}/chat/history`)
    conversations.value = Array.isArray(data)
      ? data
      : data.conversations || []
  } catch (error) {
    console.error('Error cargando historial:', error)
  } finally {
    loading.value = false
  }
}

const fetchConversation = async (id) => {
  try {
    const { data } = await axios.get(`${API_BASE_URL}/chat/${id}`)
    emit('select', data.conversation)
  } catch (error) {
    console.error('Error cargando conversación:', error)
  }
}

const startNewChat = () => {
  emit('select', { id: null, title: 'Chat nuevo', messages: [] })
}

const formatDate = (date) =>
  new Date(date).toLocaleString('es-CO', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })

onMounted(fetchHistory)
</script>

