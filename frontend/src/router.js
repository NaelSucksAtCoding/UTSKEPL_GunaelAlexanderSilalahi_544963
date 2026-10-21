import { createRouter, createWebHistory } from 'vue-router'
import { auth } from './auth'
import LoginView from './views/LoginView.vue'
import BookingView from './views/BookingView.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', redirect: '/bookings' },
    { path: '/login', name: 'login', component: LoginView, meta: { guest: true } },
    { path: '/bookings', name: 'bookings', component: BookingView, meta: { requiresAuth: true } },
  ],
})

router.beforeEach((to) => {
  if (to.meta.requiresAuth && !auth.isLoggedIn) {
    return { name: 'login' }
  }

  if (to.meta.guest && auth.isLoggedIn) {
    return { name: 'bookings' }
  }
})

export default router
