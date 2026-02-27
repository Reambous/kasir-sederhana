<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Super Admin
        User::create([
            'nama'     => 'admin', // Ganti jadi 'nama' jika di databasemu pakai kolom 'nama'
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // 2. Akun Admin Gudang
        User::create([
            'nama'     => 'haidar', // Ganti jadi 'nama' jika di databasemu pakai kolom 'nama'
            'email'    => 'haidar@gmail.com',
            'password' => Hash::make('haidar123'),
            'role'     => 'gudang',
        ]);

        // 3. Akun Kasir
        User::create([
            'nama'     => 'anas', // Ganti jadi 'nama' jika di databasemu pakai kolom 'nama'
            'email'    => 'anas@gmail.com',
            'password' => Hash::make('anas12345'),
            'role'     => 'kasir',
        ]);

        $this->command->info('Berhasil membuat 3 akun: Admin, Gudang, dan Kasir!');

        $this->call([
            // Pastikan akun user dibuat duluan karena product butuh user_id
            // UserSeeder::class, (Jika kamu memisahkan user seeder)
            ProductSeeder::class,
        ]);
    }
}
