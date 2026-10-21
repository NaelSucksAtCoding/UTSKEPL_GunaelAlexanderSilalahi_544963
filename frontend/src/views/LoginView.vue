<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api'
import { auth } from '../auth'

const router = useRouter()

const form = reactive({
  email: '',
  password: '',
})
const errors = ref({})
const pesan = ref('')
const loading = ref(false)

async function login() {
  loading.value = true
  errors.value = {}
  pesan.value = ''

  try {
    const { data } = await api.post('/login', form)
    auth.simpan(data.token, data.user)
    router.push({ name: 'bookings' })
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors ?? {}
    } else {
      pesan.value = 'Tidak dapat terhubung ke server API.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="login-wrap">
    <form class="card login-card" @submit.prevent="login">
      <div class="login-head">
        <div class="login-logo">⚽</div>
        <h1>Booking Lapangan Futsal</h1>
        <p class="muted">Masuk untuk mengelola booking</p>
      </div>

      <p v-if="pesan" class="alert">{{ pesan }}</p>

      <label class="field">
        <span>Email</span>
        <input v-model="form.email" type="email" autocomplete="username" placeholder="admin@futsal.test" />
        <small v-if="errors.email" class="error">{{ errors.email[0] }}</small>
      </label>

      <label class="field">
        <span>Password</span>
        <input v-model="form.password" type="password" autocomplete="current-password" placeholder="••••••••" />
        <small v-if="errors.password" class="error">{{ errors.password[0] }}</small>
      </label>

      <button class="btn btn-primary btn-block" type="submit" :disabled="loading">
        {{ loading ? 'Memproses...' : 'Login' }}
      </button>
    </form>
  </div>
</template>
