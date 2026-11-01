<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('objects', function (Blueprint $table) {
            $table->id();
            $table->string('disk')->default('public');
            $table->string('key')->unique();
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->foreignUuid('uploader_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('gambar_object_id')->nullable()->after('gambar')
                ->constrained('objects')->nullOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('gambar_object_id')->nullable()->after('gambar')
                ->constrained('objects')->nullOnDelete();
        });

        $this->backfill('products', 'user_id');
        $this->backfill('users', 'id');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('gambar');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gambar');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('gambar')->nullable()->after('gambar_object_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->text('gambar')->nullable()->after('gambar_object_id');
        });

        foreach (['products', 'users'] as $table) {
            DB::table($table)
                ->whereNotNull('gambar_object_id')
                ->join('objects', 'objects.id', '=', $table . '.gambar_object_id')
                ->update([$table . '.gambar' => DB::raw('objects.key')]);
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('gambar_object_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('gambar_object_id');
        });

        Schema::dropIfExists('objects');
    }

    private function backfill(string $table, string $ownerColumn): void
    {
        $rows = DB::table($table)->whereNotNull('gambar')->get();

        foreach ($rows as $row) {
            $key = $row->gambar;

            if ($key === '' || DB::table('objects')->where('key', $key)->exists()) {
                DB::table($table)->where('id', $row->id)->update(['gambar_object_id' => null]);
                continue;
            }

            $disk = 'public';
            $exists = Storage::disk($disk)->exists($key);

            $objectId = DB::table('objects')->insertGetId([
                'disk' => $disk,
                'key' => $key,
                'original_name' => basename($key),
                'mime_type' => $exists ? (Storage::disk($disk)->mimeType($key) ?: 'application/octet-stream') : 'application/octet-stream',
                'size' => $exists ? Storage::disk($disk)->size($key) : 0,
                'uploader_id' => $row->{$ownerColumn},
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table($table)->where('id', $row->id)->update(['gambar_object_id' => $objectId]);
        }
    }
};
