<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'MAKANAN',
            'MINUMAN',
            'SEMBAKO & BERAS',
            'BUMBU DAPUR',
            'KEBUTUHAN MANDI',
            'ALAT TULIS ',
            'ELEKTRONIK & KABEL',
            'OBAT & P3K',
            'PEMBERSIH RUMAH',
            'PERLENGKAPAN BAYI',
            'MAKANAN INSTAN',
            'ROTI & KUE'
        ];

        foreach ($tags as $tag) {
            // Menggunakan firstOrCreate agar tidak ada data ganda jika di-seed ulang
            Tag::firstOrCreate([
                'nama' => $tag
            ]);
        }

        $this->command->info('12 Kategori (Tags) berhasil ditambahkan!');
    }
}
