<?php

// Pesan validasi bahasa Indonesia untuk aturan yang dipakai di API.
// Aturan lain yang tidak ada di sini akan memakai pesan bawaan bahasa Inggris.
return [
    'after' => ':attribute harus setelah :date.',
    'between' => [
        'numeric' => ':attribute harus di antara :min sampai :max.',
    ],
    'date_format' => ':attribute harus berformat :format.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'in' => ':attribute yang dipilih tidak valid.',
    'integer' => ':attribute harus berupa angka bulat.',
    'max' => [
        'string' => ':attribute maksimal :max karakter.',
    ],
    'min' => [
        'numeric' => ':attribute minimal :min.',
    ],
    'required' => ':attribute wajib diisi.',
    'string' => ':attribute harus berupa teks.',

    'attributes' => [
        'nama_penyewa' => 'Nama penyewa',
        'nomor_lapangan' => 'Nomor lapangan',
        'jam_mulai' => 'Jam mulai',
        'jam_selesai' => 'Jam selesai',
        'total_harga' => 'Total harga',
        'status_pembayaran' => 'Status pembayaran',
        'email' => 'Email',
        'password' => 'Password',
    ],
];
