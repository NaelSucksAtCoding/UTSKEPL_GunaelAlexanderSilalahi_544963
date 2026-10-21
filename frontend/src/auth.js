import { reactive } from 'vue'

const TOKEN_KEY = 'token'
const USER_KEY = 'user'

// State login dibagikan ke seluruh aplikasi dan disimpan di localStorage
// supaya token tetap ada walaupun halaman di-refresh.
export const auth = reactive({
  token: localStorage.getItem(TOKEN_KEY),
  user: JSON.parse(localStorage.getItem(USER_KEY) ?? 'null'),

  get isLoggedIn() {
    return Boolean(this.token)
  },

  simpan(token, user) {
    this.token = token
    this.user = user
    localStorage.setItem(TOKEN_KEY, token)
    localStorage.setItem(USER_KEY, JSON.stringify(user))
  },

  hapus() {
    this.token = null
    this.user = null
    localStorage.removeItem(TOKEN_KEY)
    localStorage.removeItem(USER_KEY)
  },
})
