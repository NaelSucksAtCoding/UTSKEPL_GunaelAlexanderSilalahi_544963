<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookingApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function dataBooking(array $override = []): array
    {
        return array_merge([
            'nama_penyewa' => 'Budi Santoso',
            'nomor_lapangan' => 1,
            'jam_mulai' => '2026-11-01 19:00',
            'jam_selesai' => '2026-11-01 21:00',
            'total_harga' => 300000,
            'status_pembayaran' => 'dp',
        ], $override);
    }

    public function test_akses_booking_tanpa_token_mengembalikan_401(): void
    {
        $this->getJson('/api/bookings')->assertStatus(401);
        $this->postJson('/api/bookings', $this->dataBooking())->assertStatus(401);
    }

    public function test_tampil_semua_booking(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Booking::factory()->count(3)->create();

        $this->getJson('/api/bookings')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_tampil_satu_booking(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $booking = Booking::factory()->create();

        $this->getJson("/api/bookings/{$booking->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $booking->id)
            ->assertJsonPath('data.nama_penyewa', $booking->nama_penyewa);
    }

    public function test_tampil_booking_yang_tidak_ada_mengembalikan_404(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/bookings/999')->assertNotFound();
    }

    public function test_tambah_booking_mengembalikan_201(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/bookings', $this->dataBooking())
            ->assertCreated()
            ->assertJsonPath('data.nama_penyewa', 'Budi Santoso')
            ->assertJsonPath('data.jam_mulai', '2026-11-01 19:00');

        $this->assertDatabaseHas('bookings', ['nama_penyewa' => 'Budi Santoso', 'nomor_lapangan' => 1]);
    }

    public function test_tambah_booking_dengan_data_tidak_valid_mengembalikan_422(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/bookings', [
            'nomor_lapangan' => 'satu',
            'jam_mulai' => '2026-11-01 21:00',
            'jam_selesai' => '2026-11-01 19:00',
            'status_pembayaran' => 'gratis',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nama_penyewa', 'nomor_lapangan', 'jam_selesai', 'total_harga', 'status_pembayaran']);

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_tambah_booking_yang_bentrok_mengembalikan_422(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $this->postJson('/api/bookings', $this->dataBooking())->assertCreated();

        $this->postJson('/api/bookings', $this->dataBooking([
            'jam_mulai' => '2026-11-01 20:00',
            'jam_selesai' => '2026-11-01 22:00',
        ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('jam_mulai');

        // Jam yang bersambung (mulai tepat saat booking lain selesai) tetap boleh.
        $this->postJson('/api/bookings', $this->dataBooking([
            'jam_mulai' => '2026-11-01 21:00',
            'jam_selesai' => '2026-11-01 22:00',
        ]))->assertCreated();
    }

    public function test_ubah_booking(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $booking = Booking::factory()->create(['status_pembayaran' => 'dp']);

        $this->putJson("/api/bookings/{$booking->id}", ['status_pembayaran' => 'lunas'])
            ->assertOk()
            ->assertJsonPath('data.status_pembayaran', 'lunas');

        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status_pembayaran' => 'lunas']);
    }

    public function test_ubah_booking_dengan_data_tidak_valid_mengembalikan_422(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $booking = Booking::factory()->create();

        $this->putJson("/api/bookings/{$booking->id}", ['total_harga' => -5000])
            ->assertStatus(422)
            ->assertJsonValidationErrors('total_harga');
    }

    public function test_hapus_booking(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $booking = Booking::factory()->create();

        $this->deleteJson("/api/bookings/{$booking->id}")
            ->assertOk()
            ->assertJson(['message' => 'Booking berhasil dihapus.']);

        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }
}
