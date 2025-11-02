<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            DB::table('products')->insert([
                [
                    'name' => 'bakmi ayam biasa',
                    'price' => 17000,
                    'description' => 'bakmi ayam original porsi biasa',
                    'photoUrl' => 'https://image2url.com/images/1761835554298-c9a231a6-2186-40bf-95e9-ae306c2db65f.png',
                    'category' => 'Food',
                    'umkmId' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'nasi goreng kampung',
                    'price' => 25500,
                    'description' => 'Nasi goreng dengan bumbu spesial dan sayuran segar.',
                    'photoUrl' => 'https://image2url.com/images/1761835826194-fc64c1da-3995-4c73-b150-e1ba433156b5.png',
                    'category' => 'Food',
                    'umkmId' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'nasi goreng kampung',
                    'price' => 25500,
                    'description' => 'Nasi goreng dengan bumbu spesial dan sayuran segar.',
                    'photoUrl' => 'https://image2url.com/images/1761835826194-fc64c1da-3995-4c73-b150-e1ba433156b5.png',
                    'category' => 'Food',
                    'umkmId' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'nasi goreng kampung dari id 2',
                    'price' => 22000,
                    'description' => 'Nasi goreng dengan bumbu spesial toko id 2 dan sayuran segar.',
                    'photoUrl' => 'https://image2url.com/images/1761835826194-fc64c1da-3995-4c73-b150-e1ba433156b5.png',
                    'category' => 'Food',
                    'umkmId' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'bakmi ayam luar biasa',
                    'price' => 21000,
                    'description' => 'bakmi ayam original porsi biasa',
                    'photoUrl' => 'https://image2url.com/images/1761835554298-c9a231a6-2186-40bf-95e9-ae306c2db65f.png',
                    'category' => 'Food',
                    'umkmId' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Nasi Chicken Katsu',
                    'price' => 17000,
                    'description' => 'Nasi dengan tambahan chicken katsu renyah dan saus spesial.',
                    'photoUrl' => 'https://image2url.com/images/1761835826194-fc64c1da-3995-4c73-b150-e1ba433156b5.png',
                    'category' => 'Food',
                    'umkmId' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Nasi Shilin',
                    'price' => 16000,
                    'description' => 'Nasi dengan tambahan ayam tepung renyah.',
                    'photoUrl' => 'https://image2url.com/images/1761835826194-fc64c1da-3995-4c73-b150-e1ba433156b5.png',
                    'category' => 'Food',
                    'umkmId' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Bakmi Ayam Spesial',
                    'price' => 16000,
                    'description' => 'Bakmi dengan Ayam Kecap di Binus.',
                    'photoUrl' => 'https://image2url.com/images/1761835554298-c9a231a6-2186-40bf-95e9-ae306c2db65f.png',
                    'category' => 'Food',
                    'umkmId' => 4,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
