<?php

namespace App\Http\Resources;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Booking
 */
class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_penyewa' => $this->nama_penyewa,
            'nomor_lapangan' => $this->nomor_lapangan,
            'jam_mulai' => $this->jam_mulai->format('Y-m-d H:i'),
            'jam_selesai' => $this->jam_selesai->format('Y-m-d H:i'),
            'total_harga' => $this->total_harga,
            'status_pembayaran' => $this->status_pembayaran,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
