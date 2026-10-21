<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Futsal',
            'email' => 'admin@futsal.test',
            'password' => 'password',
        ]);

        Booking::create([
            'nama_penyewa' => 'Budi Santoso',
            'nomor_lapangan' => 1,
            'jam_mulai' => '2026-10-25 19:00',
            'jam_selesai' => '2026-10-25 21:00',
            'total_harga' => 300000,
            'status_pembayaran' => 'lunas',
        ]);

        Booking::create([
            'nama_penyewa' => 'Siti Rahma',
            'nomor_lapangan' => 2,
            'jam_mulai' => '2026-10-25 16:00',
            'jam_selesai' => '2026-10-25 17:00',
            'total_harga' => 150000,
            'status_pembayaran' => 'dp',
        ]);

        Booking::create([
            'nama_penyewa' => 'Andi Pratama',
            'nomor_lapangan' => 1,
            'jam_mulai' => '2026-10-26 20:00',
            'jam_selesai' => '2026-10-26 22:00',
            'total_harga' => 300000,
            'status_pembayaran' => 'belum_bayar',
        ]);
    }
}
