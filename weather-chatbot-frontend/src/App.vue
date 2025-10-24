<template>
  <div class="min-h-screen bg-gray-100 flex justify-center items-center p-6">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-3xl overflow-hidden">
      <!-- Header -->
      <div class="bg-blue-600 text-white py-4 px-6 flex justify-between items-center">
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-400" viewBox="0 0 24 24" fill="currentColor">
            <path d="M6.995 12c0 2.761 2.246 5 5.005 5a5 5 0 100-10c-2.759 0-5.005 2.239-5.005 5zm13.004 0h2v-2h-2v2zM2 12h2v-2H2v2zm9-9h2V1h-2v2zm0 20h2v-2h-2v2zm8.071-14.071l1.414-1.414-1.414-1.415-1.414 1.415 1.414 1.414zM4.929 19.071l1.414-1.414-1.414-1.415L3.515 17.656l1.414 1.415zm14.142 0l1.414-1.414-1.414-1.415-1.414 1.415 1.414 1.414zM4.929 4.929l1.414 1.414L3.515 7.758 2.1 6.344l2.829-1.415z"/>
          </svg>
          <h1 class="text-lg font-semibold">Weather Chatbot</h1>
        </div>

        <button
          v-if="currentChat"
          @click="exitChat"
          class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-sm transition"
        >
          ↩ Volver
        </button>
      </div>

      <!-- Contenido principal -->
      <transition name="fade" mode="out-in">
        <div key="history" v-if="!currentChat" class="p-6 bg-gray-50">
          <ChatHistory @select="openChat" />
        </div>

        <div key="chat" v-else class="p-6 bg-gray-50">
          <ChatContainer :chat="currentChat" @exit="exitChat" />
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ChatHistory from './components/ChatHistory.vue'
import ChatContainer from './components/ChatContainer.vue'

const currentChat = ref(null)

const openChat = (chat) => {
  currentChat.value = chat
}

const exitChat = () => {
  currentChat.value = null
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

