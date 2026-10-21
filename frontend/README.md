# Frontend Booking Lapangan Futsal (Vue.js)

Frontend Vue 3 + Vite + Axios untuk API Laravel di folder induk (soal nomor 1).

## Menjalankan

Pastikan API Laravel sudah berjalan (`php artisan serve` di folder induk), lalu:

```bash
cd frontend
cp .env.example .env
npm install
npm run dev
```

Buka http://localhost:5173 dan login dengan `admin@futsal.test` / `password`.

## Konfigurasi

Alamat API diatur lewat variabel lingkungan `VITE_API_URL` di file `.env`, bukan ditulis di dalam komponen:

```
VITE_API_URL=http://127.0.0.1:8000/api
```

Nilai ini dibaca di `src/config.js` lalu dipakai oleh instance Axios di `src/api.js`.

## Struktur

| File | Isi |
|---|---|
| `src/config.js` | Membaca `VITE_API_URL` dari `.env` |
| `src/auth.js` | Menyimpan token dan data user di `localStorage` |
| `src/api.js` | Instance Axios: `baseURL` dari konfigurasi, menyisipkan `Authorization: Bearer <token>` di setiap request, kembali ke login jika 401 |
| `src/router.js` | Route `/login` dan `/bookings`, halaman booking hanya bisa dibuka setelah login |
| `src/views/LoginView.vue` | Halaman login |
| `src/views/BookingView.vue` | Halaman tampil, tambah, ubah, dan hapus booking |
