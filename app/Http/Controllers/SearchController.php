<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class SearchController extends Controller
{
    private string $aiUrl;
    private string $promptTemplate;

    public function __construct()
    {
        $this->aiUrl = env("AI_SERVICE_URL");

        $this->promptTemplate = <<<PROMPT
        Kamu adalah asisten pencarian untuk aplikasi UMKM yang memiliki data produk dan informasi UMKM.

        Tugasmu adalah menerjemahkan input pengguna dalam bahasa alami menjadi JSON terstruktur untuk pencarian.

        Database memiliki dua tabel:
        - products: name, price, description, category, umkmId
        - umkms: name, type, address, rating, latitude, longitude

        Output JSON HARUS dalam format berikut:

        {
            "search_target": "product" | "umkm",
            "category": string | null,
            "max_price": number | null,
            "min_price": number | null,
            "min_rating": number | null,
            "location": string | null,
            "keywords": string[] | null,
            "near_me": boolean
        }

        ---

        Aturan tambahan:
        - Jika pengguna menyebut “dekat saya”, “sekitar sini”, “terdekat”, atau “sekitar saya”, maka "near_me": true dan "location": null.
        - Jika pengguna menyebut kota atau daerah tertentu (misalnya “Bandung” atau “Jakarta”), maka "location" diisi dengan nama kota tersebut dan "near_me": false.
        - Jika pengguna mencari berdasarkan nama toko, jenis usaha, atau kata seperti “UMKM”, “toko”, “warung”, maka "search_target": "umkm".
        - Jika pengguna mencari makanan, minuman, atau barang tertentu (misal “nasi goreng murah”, “kopi dingin”), maka "search_target": "product".

        ---

        input :
        PROMPT;

    }

    public function searchWithQuery(Request $request)
    {
        $query = $request->query("query");
        $lat = $request->query("lat");
        $lng = $request->query("lng");

        if (!$query) {
            return response()->json([
                "error" => "Parameter 'query' wajib diisi."
            ], 400);
        }

        try {
            $aiResponse = $this->askGemini($query);

            $parsed = json_decode($aiResponse, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning("AI response bukan JSON valid", ["response" => $aiResponse]);
                return response()->json([
                    "error" => "Gagal memproses hasil dari AI service.",
                    "raw_response" => $aiResponse
                ], 500);
            }

            $parsed["user_latitude"] = $lat ?? null;
            $parsed["user_longitude"] = $lng ?? null;


            Log::info("AI parsing berhasil", [
                "query" => $query,
                "parsed" => $parsed
            ]);


            if ($parsed["search_target"] === "umkm") {
                $filteredProducts = $this->filterUmkms($parsed);
            } else {
                $filteredProducts = $this->filterUmkmProducts($parsed);
            }

            if (count($filteredProducts) > 0) {
                return response()->json([
                    "data" => $filteredProducts
                ], 200);
            } else {
                return response()->json([
                    "message" => "no umkm found."
                ], 404);
            }

        } catch (Exception $e) {
            Log::error("Gagal memanggil AI service", [
                "message" => $e->getMessage(),
                "trace" => $e->getTraceAsString()
            ]);

            return response()->json([
                "error" => "Terjadi kesalahan saat menghubungi AI service.",
                "details" => $e->getMessage()
            ], 500);
        }
    }

    private function askGemini(string $query): string
    {
        $fullPrompt = $this->promptTemplate . $query;

        $response = Http::timeout(15)->post($this->aiUrl . "/ask-gemini", [
            "prompt" => $fullPrompt
        ]);

        if ($response->failed()) {
            Log::error("AI Service failed", [
                "status" => $response->status(),
                "body" => $response->body()
            ]);
            throw new Exception("AI service failed (status {$response->status()})");
        }

        return $response->body();
    }

    private function filterUmkmProducts(array $filters) {
        $query = Umkm::query()
            ->select('products.*', 'umkms.name as umkm_name', 'umkms.address', 'umkms.latitude', 'umkms.longitude', 'umkms.rating')
            ->join('umkms', 'products.umkmId', '=', 'umkms.id');

        if (!empty($filters['category'])) {
            $query->where('products.category', 'LIKE', '%' . $filters['category'] . '%');
        }


        if (!empty($filters['min_price'])) {
            $query->where('products.price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('products.price', '<=', $filters['max_price']);
        }
        if (!empty($filters['keywords'])) {
            $query->where(function ($q) use ($filters) {
                foreach ($filters['keywords'] as $word) {
                    $q->orWhere('products.name', 'LIKE', '%' . $word . '%')
                    ->orWhere('products.description', 'LIKE', '%' . $word . '%');
                }
            });
        }

        if (!empty($filters['location'])) {
            $query->where('umkms.address', 'LIKE', '%' . $filters['location'] . '%');
        }

        if (!empty($filters['min_rating'])) {
            $query->where('umkms.rating', '>=', $filters['min_rating']);
        }

        if (!empty($filters['near_me']) && $filters['near_me'] === true && !empty($filters['user_latitude']) && !empty($filters['user_longitude'])) {
            $userLat = $filters['user_latitude'];
            $userLng = $filters['user_longitude'];

            $haversine = "(6371 * acos(cos(radians($userLat))
                            * cos(radians(umkms.latitude))
                            * cos(radians(umkms.longitude) - radians($userLng))
                            + sin(radians($userLat))
                            * sin(radians(umkms.latitude))))";

            $query->selectRaw("$haversine AS distance")
                ->orderBy('distance', 'asc')
                ->having('distance', '<=', 10);
        }


        $query->orderBy('products.price', 'asc');


        return $query->get();
    }

    private function filterUmkms(array $filters){
        $query = Umkm::query();

        if (!empty($filters['category'])) {
            $query->where('type', 'LIKE', '%' . $filters['category'] . '%');
        }

        if (!empty($filters['keywords'])) {
            $query->where(function ($q) use ($filters) {
                foreach ($filters['keywords'] as $word) {
                    $q->orWhere('name', 'LIKE', '%' . $word . '%')
                        ->orWhere('description', 'LIKE', '%' . $word . '%')
                        ->orWhere('address', 'LIKE', '%' . $word . '%');
                }
            });
        }

        if (!empty($filters['location'])) {
            $query->where('address', 'LIKE', '%' . $filters['location'] . '%');
        }

        if (!empty($filters['min_rating'])) {
            $query->where('rating', '>=', $filters['min_rating']);
        }

        if (!empty($filters['near_me']) && $filters['near_me'] === true &&
            !empty($filters['user_latitude']) && !empty($filters['user_longitude'])) {

            $userLat = $filters['user_latitude'];
            $userLng = $filters['user_longitude'];

            $haversine = "(6371 * acos(cos(radians($userLat))
                            * cos(radians(latitude))
                            * cos(radians(longitude) - radians($userLng))
                            + sin(radians($userLat))
                            * sin(radians(latitude))))";

            $query->select('*')
                ->selectRaw("$haversine AS distance")
                ->orderBy('distance', 'asc')
                ->having('distance', '<=', 10);
        }

        return $query->get();
    }

    public function searchByKeyword(Request $request){
        $search = $request->query('keyword');
        $umkmResults = $this->filterUmkms(['keywords' => [$search]]);

        if(count($umkmResults) === 0){
            return response()->json([
                'message' => 'no umkm found.'
            ], 404);
        }

        return response()->json([
            'umkms' => $umkmResults
        ]);
    }
}
