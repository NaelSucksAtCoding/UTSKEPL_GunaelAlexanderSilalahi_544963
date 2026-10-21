<script setup>
import { useRouter } from 'vue-router'
import api from './api'
import { auth } from './auth'

const router = useRouter()

async function logout() {
  try {
    await api.post('/logout')
  } finally {
    auth.hapus()
    router.push({ name: 'login' })
  }
}
</script>

<template>
  <header v-if="auth.isLoggedIn" class="topbar">
    <div class="brand">⚽ Booking Lapangan Futsal</div>
    <div class="topbar-right">
      <span class="muted">{{ auth.user?.name }}</span>
      <button class="btn btn-ghost" @click="logout">Logout</button>
    </div>
  </header>

  <main>
    <RouterView />
  </main>
</template>
