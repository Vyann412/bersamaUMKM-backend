<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('umkms')) {
            DB::table('umkms')->insert([
                [
                    'type' => 'Food',
                    'name' => 'Mamie D’Jempol',
                    'photoUrl' => 'https://image2url.com/images/1761484272356-f87f8f82-86a3-448b-90f5-44e938182d93.jpg',
                    'description' => 'Mamie D’Jempol adalah usaha UMKM yang berada di Silkwood, Alam Sutera, Tangerang. Usaha ini bergerak di bidang kuliner',
                    'latitude' => -6.22524,
                    'longitude' => 106.65086,
                    'address' => 'Alam Sutera, Jl. Lingkar Barat, Panunggangan Timur, Kecamatan Pinang, Kota, Kota Tangerang, Banten 15143',
                    'rating' => null
                ],
                [
                    'type' => 'Food',
                    'name' => 'Bakso Gepeng Kiki',
                    'photoUrl' => 'https://image2url.com/images/1761485202600-0d901b85-6a25-4339-9b54-65c4015ae6ce.jpg',
                    'description' => 'Bakso Gepeng Kiki adalah usaha UMKM yang berada di Binus, Alam Sutera, Tangerang. Usaha ini bergerak di bidang kuliner',
                    'latitude' => -6.21942,
                    'longitude' => 106.64853,
                    'address' => 'Alam Sutera, Binus University Alam Sutera',
                    'rating' => null
                ],
                [
                    'type' => 'Food',
                    'name' => 'Nara Kitchen',
                    'photoUrl' => 'https://image2url.com/images/1761485480498-f4b44bfc-311d-4bc7-88e8-2097309a96cd.jpg',
                    'description' => 'Nara Kitchen adalah usaha UMKM yang berada di Alfa Tower, Alam Sutera, Tangerang. Usaha ini bergerak di bidang kuliner',
                    'latitude' => -6.22572,
                    'longitude' => 106.65740,
                    'address' => 'Alfa Tower, Alam Sutera, Tangerang',
                    'rating' => null
                ],
                [
                    'type' => 'Food',
                    'name' => 'Bakmi Kinara',
                    'photoUrl' => 'https://image2url.com/images/1761485664622-1a85c3b8-19ac-4d7b-9942-6202e258e909.jpg',
                    'description' => 'Bakmi Kinara adalah usaha UMKM yang berada di Binus, Alam Sutera, Tangerang. Usaha ini bergerak di bidang kuliner',
                    'latitude' => -6.21942,
                    'longitude' => 106.64853,
                    'address' => 'Alam Sutera, Binus University Alam Sutera',
                    'rating' => null
                ],
                [
                    'type'=> 'Food',
                    'name' => 'Bakmi Kinara',
                    'photoUrl' => 'https://image2url.com/images/1761485664622-1a85c3b8-19ac-4d7b-9942-6202e258e909.jpg',
                    'description' => 'Bakmi Kinara adalah usaha UMKM yang berada di Binus, Alam Sutera, Tangerang. Usaha ini bergerak di bidang kuliner',
                    'latitude' => -6.21942,
                    'longitude' => 106.64853,
                    'address' => 'Alam Sutera, Binus University Alam Sutera',
                    'rating' => null
                ],
                                [
                    'type'=> 'Food',
                    'name' => 'Bakmi Kinara',
                    'photoUrl' => 'https://image2url.com/images/1761485664622-1a85c3b8-19ac-4d7b-9942-6202e258e909.jpg',
                    'description' => 'Bakmi Kinara adalah usaha UMKM yang berada di Binus, Alam Sutera, Tangerang. Usaha ini bergerak di bidang kuliner',
                    'latitude' => -6.21942,
                    'longitude' => 106.64853,
                    'address' => 'Jl. Jalur Sutera No.Kav. 15, Pakualam, Kec. Serpong Utara, Kota Tangerang Selatan, Banten 15325',
                    'rating'=> null
                ],
                [
                    'type'=> 'Food',
                    'name' => 'Bakmi Kinara',
                    'photoUrl' => 'https://image2url.com/images/1761485664622-1a85c3b8-19ac-4d7b-9942-6202e258e909.jpg',
                    'description' => 'Bakmi Kinara adalah usaha UMKM yang berada di Binus, Alam Sutera, Tangerang. Usaha ini bergerak di bidang kuliner',
                    'latitude' => -6.21942,
                    'longitude' => 106.64853,
                    'address' => 'Alam Sutera, Binus University Alam Sutera',
                    'rating' => null
                ]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
