// Semua konfigurasi aplikasi dibaca dari variabel lingkungan (file .env),
// sehingga alamat API tidak ditulis mati di dalam komponen.
export const API_URL = import.meta.env.VITE_API_URL

if (!API_URL) {
  console.error('VITE_API_URL belum diatur. Salin .env.example menjadi .env lalu jalankan ulang npm run dev.')
}
