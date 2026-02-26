<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pastikan ada minimal 1 Admin Gudang (karena butuh user_id berformat UUID)
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'id' => Str::uuid(),
                'nama' => 'Admin Gudang',
                'email' => 'gudang@toko.com',
                'password' => bcrypt('password123'),
                'role' => 'admin_gudang',
            ]);
        }

        // 2. Siapkan beberapa kategori Tag untuk direlasikan
        $tags = [
            Tag::firstOrCreate(['nama' => 'Makanan Ringan']),
            Tag::firstOrCreate(['nama' => 'Minuman']),
            Tag::firstOrCreate(['nama' => 'Sembako']),
            Tag::firstOrCreate(['nama' => 'Kebutuhan Mandi']),
        ];

        // 3. Siapkan 10 Data Barang Realistis
        $productsData = [
            ['nama' => 'ayam', 'barcode' => '899800901', 'jumlah' => 150, 'harga_beli' => 2500, 'harga_jual' => 3000, 'tags' => [0, 2]],
            ['nama' => 'nasi ayam', 'barcode' => '899880012', 'jumlah' => 200, 'harga_beli' => 2000, 'harga_jual' => 3500, 'tags' => [1]],
            ['nama' => 'sosro', 'barcode' => '89960013', 'jumlah' => 120, 'harga_beli' => 2800, 'harga_jual' => 4000, 'tags' => [1]],
            ['nama' => 'dada', 'barcode' => '899100233', 'jumlah' => 50, 'harga_beli' => 9000, 'harga_jual' => 11500, 'tags' => [0]],
            ['nama' => 'paha', 'barcode' => '899300564', 'jumlah' => 75, 'harga_beli' => 4500, 'harga_jual' => 5500, 'tags' => [0]],
            ['nama' => 'sayap', 'barcode' => '899400675', 'jumlah' => 40, 'harga_beli' => 12000, 'harga_jual' => 14500, 'tags' => [3]],
            ['nama' => 'buku hujan', 'barcode' => '89950076', 'jumlah' => 30, 'harga_beli' => 20000, 'harga_jual' => 24000, 'tags' => [3]],
            ['nama' => 'marimas', 'barcode' => '899600897', 'jumlah' => 25, 'harga_beli' => 65000, 'harga_jual' => 70000, 'tags' => [2]],
            ['nama' => 'minyak goreng', 'barcode' => '899700908', 'jumlah' => 60, 'harga_beli' => 32000, 'harga_jual' => 36000, 'tags' => [2]],
            ['nama' => 'rokok 76', 'barcode' => '899800019', 'jumlah' => 45, 'harga_beli' => 14000, 'harga_jual' => 17500, 'tags' => [0]],
        ];

        // 4. Masukkan ke Database dan Sambungkan dengan Tags
        foreach ($productsData as $data) {
            $product = Product::create([
                'nama' => $data['nama'],
                'barcode' => $data['barcode'],
                'jumlah' => $data['jumlah'],
                'harga_beli' => $data['harga_beli'],
                'harga_jual' => $data['harga_jual'],
                'tanggal_masuk' => Carbon::now()->subDays(rand(1, 30))->toDateString(), // Acak tanggal masuk dalam 30 hari terakhir
                'user_id' => $user->id,
            ]);

            // Menyambungkan barang dengan Tag di tabel pivot (product_tag)
            $tagIdsToAttach = [];
            foreach ($data['tags'] as $tagIndex) {
                $tagIdsToAttach[] = $tags[$tagIndex]->id;
            }
            $product->tags()->attach($tagIdsToAttach);
        }

        $this->command->info('10 Data Barang (Products) dan Relasi Tag berhasil dibuat!');
    }
}
