<?php

namespace App\Models;

use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

#[Fillable([
    'nama_penyewa',
    'nomor_lapangan',
    'jam_mulai',
    'jam_selesai',
    'total_harga',
    'status_pembayaran',
])]
class Booking extends Model
{
    /** @use HasFactory<BookingFactory> */
    use HasFactory;

    public const STATUS_PEMBAYARAN = ['belum_bayar', 'dp', 'lunas'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nomor_lapangan' => 'integer',
            'jam_mulai' => 'datetime',
            'jam_selesai' => 'datetime',
            'total_harga' => 'integer',
        ];
    }

    /**
     * Cek apakah lapangan sudah dibooking pada rentang waktu tersebut.
     */
    public static function isBentrok(int $nomorLapangan, string $jamMulai, string $jamSelesai, ?int $kecualiId = null): bool
    {
        return static::query()
            ->where('nomor_lapangan', $nomorLapangan)
            ->where('jam_mulai', '<', Carbon::parse($jamSelesai))
            ->where('jam_selesai', '>', Carbon::parse($jamMulai))
            ->when($kecualiId, fn ($query) => $query->whereKeyNot($kecualiId))
            ->exists();
    }
}
