# Booking Lapangan Futsal

[![CI](https://github.com/NaelSucksAtCoding/uts-pl-544963/actions/workflows/ci.yml/badge.svg)](https://github.com/NaelSucksAtCoding/uts-pl-544963/actions/workflows/ci.yml)

UTS Konstruksi & Evolusi Perangkat Lunak — Gunael Alexander Silalahi (544963)

Sistem manajemen booking lapangan futsal / mini soccer, terdiri dari RESTful API Laravel dan frontend Vue.js.

## Struktur Repository

| Folder | Isi |
|---|---|
| [`backend/`](backend) | RESTful API Laravel + Sanctum, feature test, dan [Postman collection](backend/postman/Booking_Lapangan_Futsal.postman_collection.json) |
| [`frontend/`](frontend) | Aplikasi Vue.js (Vite + Axios) yang memakai API backend |
| [`.github/workflows/ci.yml`](.github/workflows/ci.yml) | GitHub Actions: test backend dan build frontend |

## Menjalankan

**Backend** (terminal 1):

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

**Frontend** (terminal 2):

```bash
cd frontend
cp .env.example .env
npm install
npm run dev
```

Buka http://localhost:5173 dan login dengan `admin@futsal.test` / `password`.

Dokumentasi lengkap: [backend/README.md](backend/README.md) dan [frontend/README.md](frontend/README.md).

## Alur Kerja Git

- `main`: kode stabil. `dev`: gabungan fitur yang sedang dikembangkan. `feat/*`: satu branch per fitur.
- Perubahan masuk ke `dev` dan `main` hanya melalui Pull Request (`feat/*` → `dev` → `main`).
- Branch protection pada `main` dan `dev` mewajibkan Pull Request dan status check CI lulus sebelum merge.

## Continuous Integration

Workflow [`ci.yml`](.github/workflows/ci.yml) berjalan pada setiap push dan pull request ke `main` dan `dev`:

| Job | Langkah |
|---|---|
| Backend (Laravel API) | `composer install` → `php artisan test` |
| Frontend (Vue.js) | `npm ci` → `npm run build` |
