<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil User pertama (Admin) untuk user_id
        $user = User::first();

        // 2. Buat beberapa kategori contoh jika belum ada
        $tags = ['Makanan', 'Minuman', 'Elektronik', 'Kebutuhan Rumah', 'Alat Tulis'];
        foreach ($tags as $tagName) {
            Tag::firstOrCreate(['nama' => $tagName]);
        }
        $allTags = Tag::all();

        // 3. Data Produk Contoh (40 Item)
        $namaProduk = [
            'Indomie Goreng',
            'Indomie Kari Ayam',
            'Sedaap Soto',
            'Beras Ramos 5kg',
            'Minyak Goreng 2L',
            'Gula Pasir 1kg',
            'Garam Dapur',
            'Kecap Manis ABC',
            'Susu UHT Cokelat',
            'Teh Kotak',
            'Kopi Kapal Api',
            'Air Mineral 600ml',
            'Coca Cola 1.5L',
            'Pringles Original',
            'Chitato Sapi Panggang',
            'Oreo Pack',
            'Sabun Cuci Piring',
            'Deterjen Bubuk',
            'Pembersih Lantai',
            'Shampo Botol',
            'Sabun Mandi Cair',
            'Pasta Gigi',
            'Sikat Gigi Soft',
            'Tissue Wajah',
            'Baterai AA',
            'Lampu LED 9W',
            'Kabel Data Type-C',
            'Stop Kontak',
            'Buku Tulis A5',
            'Pulpen Gel Hitam',
            'Pensil 2B',
            'Penghapus Putih',
            'Penggaris 30cm',
            'Map Plastik',
            'Lem Kertas',
            'Gunting Sedang',
            'Sandal Jepit',
            'Payung Lipat',
            'Sapu Lantai',
            'Ember Plastik'
        ];

        foreach ($namaProduk as $index => $nama) {
            $hargaBeli = rand(10, 100) * 500; // Harga beli acak

            $product = Product::create([
                'nama' => $nama,
                'barcode' => '899' . str_pad($index + 1, 10, '0', STR_PAD_LEFT),
                'jumlah' => rand(5, 50),
                'harga_beli' => $hargaBeli,
                'harga_jual' => $hargaBeli + (rand(2, 10) * 500), // Margin keuntungan
                'tanggal_masuk' => now()->subDays(rand(1, 30)),
                'user_id' => $user->id,
            ]);

            // Tempelkan 1-2 kategori acak ke produk
            $product->tags()->attach(
                $allTags->random(rand(1, 2))->pluck('id')->toArray()
            );
        }

        $this->command->info('Berhasil membuat 40 Produk dengan kategori acak!');
    }
}
