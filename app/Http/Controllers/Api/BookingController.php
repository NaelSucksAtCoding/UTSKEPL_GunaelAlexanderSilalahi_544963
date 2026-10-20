<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookingController extends Controller
{
    /**
     * Tampilkan semua booking.
     */
    public function index(): AnonymousResourceCollection
    {
        return BookingResource::collection(Booking::orderBy('jam_mulai')->get());
    }

    /**
     * Tambah booking baru.
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        $booking = Booking::create($request->validated());

        return (new BookingResource($booking->refresh()))
            ->additional(['message' => 'Booking berhasil dibuat.'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Tampilkan satu booking.
     */
    public function show(Booking $booking): BookingResource
    {
        return new BookingResource($booking);
    }

    /**
     * Ubah data booking.
     */
    public function update(UpdateBookingRequest $request, Booking $booking): BookingResource
    {
        $booking->update($request->validated());

        return (new BookingResource($booking))
            ->additional(['message' => 'Booking berhasil diubah.']);
    }

    /**
     * Hapus booking.
     */
    public function destroy(Booking $booking): JsonResponse
    {
        $booking->delete();

        return response()->json(['message' => 'Booking berhasil dihapus.']);
    }
}
