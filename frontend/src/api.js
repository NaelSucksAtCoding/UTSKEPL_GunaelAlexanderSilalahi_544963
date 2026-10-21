import axios from 'axios'
import { API_URL } from './config'
import { auth } from './auth'
import router from './router'

const api = axios.create({
  baseURL: API_URL,
  headers: {
    Accept: 'application/json',
  },
})

// Sisipkan token pada setiap permintaan setelah login.
api.interceptors.request.use((config) => {
  if (auth.token) {
    config.headers.Authorization = `Bearer ${auth.token}`
  }

  return config
})

// Token kedaluwarsa atau tidak valid: hapus token dan kembali ke halaman login.
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401 && auth.token) {
      auth.hapus()
      router.push({ name: 'login' })
    }

    return Promise.reject(error)
  },
)

export default api
