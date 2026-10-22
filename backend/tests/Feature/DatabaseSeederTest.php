<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_mengisi_akun_admin_dan_booking_contoh(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', ['email' => 'admin@futsal.test']);
        $this->assertDatabaseCount('bookings', 3);
    }

    public function test_seeder_tidak_mengisi_ulang_data_yang_sudah_ada(): void
    {
        $this->seed(DatabaseSeeder::class);
        Booking::where('nama_penyewa', 'Siti Rahma')->delete();

        // Container backend menjalankan seeder lagi setiap kali start.
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(1, User::count());
        $this->assertDatabaseCount('bookings', 2);
        $this->assertDatabaseMissing('bookings', ['nama_penyewa' => 'Siti Rahma']);
    }
}
