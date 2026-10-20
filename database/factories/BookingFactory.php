<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $durasi = fake()->numberBetween(1, 3);
        $jamMulai = now()->addDays(fake()->numberBetween(1, 30))->setTime(fake()->numberBetween(8, 21), 0);

        return [
            'nama_penyewa' => fake()->name(),
            'nomor_lapangan' => fake()->numberBetween(1, 5),
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamMulai->copy()->addHours($durasi),
            'total_harga' => $durasi * 150000,
            'status_pembayaran' => fake()->randomElement(Booking::STATUS_PEMBAYARAN),
        ];
    }
}
