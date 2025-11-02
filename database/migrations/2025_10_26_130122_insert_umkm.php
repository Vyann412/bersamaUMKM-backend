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
                ],
                [
                    'type'=> 'Drinks',
                    'name' => 'Koffielogik',
                    'photoUrl' => 'https://image2url.com/images/1762062647182-609da39f-d769-402a-8e17-37192605c8c6.png',
                    'description' => 'Koffielogik adalah usaha UMKM yang berada di Senayan. Usaha ini bergerak di bidang beverage',
                    'latitude' => -6.228630821769731,
                    'longitude' => 106.79682898313187,
                    'address' => 'Senayan, Tanah Abang, Jakarta Pusat',
                    'rating' => null
                ],
                [
                    'type'=> 'Drinks',
                    'name' => 'Es Kode',
                    'photoUrl' => 'https://image2url.com/images/1762062919097-1e201089-49fd-4691-86de-804ba78d74ef.png',
                    'description' => 'Es Kode adalah usaha UMKM yang berada di Sukasari, Tangerang. Usaha ini bergerak di bidang beverage',
                    'latitude' => -6.1843795347430675,
                    'longitude' => 106.63446208128136,
                    'address' => 'Jl. Kh. Soleh Ali No.109, RT.004/RW.011, Sukasari, Kec. Tangerang, Kota Tangerang, Banten 15118',
                    'rating' => null
                ],
                [
                    'type'=> 'Service',
                    'name' => 'Barbar Barbershop',
                    'photoUrl' => 'https://image2url.com/images/1762063148629-cb17e0f0-9d09-439d-999b-0b13ec4f63d9.png',
                    'description' => 'Barbar Barbershop adalah usaha UMKM yang berada di Rajawati, Jakarta Selatan. Usaha ini bergerak di bidang jasa potong rambut',
                    'latitude' => -6.257545356144587,
                    'longitude' => 106.85089322513514,
                    'address' => 'Apartment Kalibata City Tower Nusa Indah, Rawajati, Kec. Pancoran, Kota Jakarta Selatan',
                    'rating' => null
                ],
                [
                    'type'=> 'Service',
                    'name' => 'Salon Smooch Beauty Bar',
                    'photoUrl' => 'https://image2url.com/images/1762063534725-4a846b3f-28db-493f-a1af-a5502d1e227a.png',
                    'description' => 'Salon Smooch Beauty Bar adalah usaha UMKM yang berada di Alam Sutera, Tangerang Selatan. Usaha ini bergerak di bidang jasa salon kecantikan',
                    'latitude' => -6.238409906294591,
                    'longitude' => 106.65183414232847,
                    'address' => 'Perumahan Alam Sutera, Komp. RUKO CRYSTAL 8, Pakualam, Kec. Serpong Utara, Kota Tangerang Selatan, Banten 15320',
                    'rating' => null
                ],
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
