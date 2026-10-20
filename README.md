# Booking Lapangan Futsal API

UTS Konstruksi & Evolusi Perangkat Lunak — Gunael Alexander Silalahi (544963)

RESTful API berbasis Laravel untuk manajemen booking lapangan futsal / mini soccer. Autentikasi memakai token Laravel Sanctum.

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Akun demo hasil seeder:

| Email | Password |
|---|---|
| admin@futsal.test | password |

## Endpoint

Semua endpoint selain `POST /api/login` wajib memakai header `Authorization: Bearer <token>`.

| Method | Endpoint | Keterangan | Status sukses |
|---|---|---|---|
| POST | `/api/login` | Login, mengembalikan token | 200 |
| GET | `/api/me` | Data user yang sedang login | 200 |
| POST | `/api/logout` | Logout, menghapus token | 200 |
| GET | `/api/bookings` | Tampil semua booking | 200 |
| POST | `/api/bookings` | Tambah booking | 201 |
| GET | `/api/bookings/{id}` | Tampil satu booking | 200 |
| PUT/PATCH | `/api/bookings/{id}` | Ubah booking | 200 |
| DELETE | `/api/bookings/{id}` | Hapus booking | 200 |

Kode status error: **401** jika token tidak ada atau tidak valid, **422** jika validasi gagal, **404** jika data tidak ditemukan.

## Data Booking

| Field | Tipe | Aturan |
|---|---|---|
| `nama_penyewa` | string | wajib, maks. 100 karakter |
| `nomor_lapangan` | integer | wajib, 1–10 |
| `jam_mulai` | datetime | wajib, format `Y-m-d H:i` |
| `jam_selesai` | datetime | wajib, format `Y-m-d H:i`, setelah `jam_mulai` |
| `total_harga` | integer | wajib, minimal 0 |
| `status_pembayaran` | string | `belum_bayar` (default), `dp`, atau `lunas` |

Booking ditolak (422) jika jadwalnya bentrok dengan booking lain di lapangan yang sama.

Contoh request tambah booking:

```json
{
    "nama_penyewa": "Rina Marlina",
    "nomor_lapangan": 3,
    "jam_mulai": "2026-10-27 18:00",
    "jam_selesai": "2026-10-27 20:00",
    "total_harga": 300000,
    "status_pembayaran": "dp"
}
```

## Postman

Import [postman/Booking_Lapangan_Futsal.postman_collection.json](postman/Booking_Lapangan_Futsal.postman_collection.json) ke Postman. Jalankan **Auth > Login** terlebih dahulu; token otomatis disimpan ke variabel collection dan dipakai oleh request lainnya.

## Testing

```bash
php artisan test
```

Feature test ada di [tests/Feature/AuthApiTest.php](tests/Feature/AuthApiTest.php) dan [tests/Feature/BookingApiTest.php](tests/Feature/BookingApiTest.php).
