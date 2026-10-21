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

## Docker

| Berkas | Isi |
|---|---|
| [`backend/Dockerfile`](backend/Dockerfile) | PHP 8.3 + Apache. `composer.json` dan `composer.lock` disalin dan dipasang sebelum kode aplikasi supaya cache lapisan bekerja |
| [`frontend/Dockerfile`](frontend/Dockerfile) | Multi-stage: Node.js membangun aplikasi, Nginx menyajikan hasil build (tanpa `node_modules`) |
| `backend/.dockerignore`, `frontend/.dockerignore` | Mengecualikan dependency, `.env`, database lokal, dan berkas yang tidak dibutuhkan |

Build dan jalankan:

```bash
docker build -t futsal-backend backend
docker build -t futsal-frontend --build-arg VITE_API_URL=http://localhost:8000/api frontend

docker run -d -p 8000:80 -e APP_KEY=<app-key> -e RUN_MIGRATIONS=true -e RUN_SEEDER=true futsal-backend
docker run -d -p 8080:80 futsal-frontend
```

Buka http://localhost:8080. Nilai rahasia seperti `APP_KEY` dan `DB_PASSWORD` tidak ditulis di Dockerfile, melainkan diberikan lewat environment variable saat container dijalankan. `APP_KEY` bisa dibuat dengan `php artisan key:generate --show` di folder `backend/`.

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
