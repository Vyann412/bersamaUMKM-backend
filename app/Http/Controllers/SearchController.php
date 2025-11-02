<?php

    namespace App\Http\Controllers;

    use App\Http\Controllers\Controller;
    use App\Models\Product;
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
            Kamu adalah sistem yang menerjemahkan query pencarian pengguna menjadi JSON query untuk pencarian UMKM dan produk.


            Database memiliki dua tabel:
            - products: name, price, description, category, umkmId
            - umkms: name, type, address, rating, latitude, longitude

            Gunakan format JSON berikut:

            ```json
            {
                "search_target": "umkm" | "product",
                "category": string | null,
                "max_price": number | null,
                "min_price": number | null,
                "min_rating": number | null,
                "location": string | null,
                "keywords": [string],
                "near_me": boolean
            }

            ---

            Aturan tambahan:
            - Perbaiki ejaan nama jika ada kesalahan ketik terutama pada nama lokasi dan nama usaha serta nama produk
                Contoh koreksi ejaan:
                - "bakmi alam sitera" → "Bakmi Alam Sutera"
                - "jakrta" → "Jakarta"
                - "bandong" → "Bandung"
            - pahami konteks pencarian berdasarkan kata kunci yang diberikan pengguna.
            - urai bentuk kata singkatan menjadi bentuk lengkapnya, contoh: "alsut" menjadi "Alam Sutera"
            - Jika pengguna menyebut “dekat saya”, “sekitar sini”, “terdekat”, atau “sekitar saya”, maka "near_me": true dan "location": null.
            - jika pengguna menyebutkan kata-kata seperti “di sekitar [lokasi]”, “dekat [lokasi]”, atau “sekitar [lokasi]”, maka "location" diisi dengan nama lokasi tersebut dan "near_me": false.
            - Jika pengguna menyebut kota atau daerah tertentu (misalnya “Bandung” atau “Jakarta”), maka "location" diisi dengan nama kota tersebut dan "near_me": false.
            - Jika input berisi nama usaha, restoran, atau kata seperti “warung”, “toko”, “tempat makan”, “kafe”, “resto”, “rumah makan”, maka "search_target": "umkm" dan "category" diset sesuai konteks (misalnya "Food" untuk tempat makan, "Fashion" untuk toko pakaian).
            - Jangan pecah frasa umum seperti “tempat makan” menjadi ["tempat", "makan"]. Gunakan "category": "Food" dan biarkan "keywords": null kecuali ada nama usaha spesifik.
            - Jika input berisi kata-kata yang mengacu pada produk (misalnya “sepatu”, “baju”, “makanan ringan”, “minuman”), maka "search_target": "product" dan "category" diset sesuai konteks.
            - kalau pencarian tidak spesifik maka kosongkan keyword, contoh:
                - "cari makanan enak di sekitar sini" → "keywords": []
                - "toko baju murah di bandung" → "keywords": []
                - "tempat nongkrong asik" → "keywords": []
            - buat keyword semuanya lowercase.



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
                Log::info("AI response diterima (raw)", ["response" => $aiResponse]);

                $parsed = json_decode($aiResponse, true);

                if (json_last_error() !== JSON_ERROR_NONE || !is_array($parsed)) {
                    Log::warning("AI response bukan JSON valid atau tidak ter-decode", ["response" => $aiResponse, "json_error" => json_last_error_msg()]);
                    return response()->json([
                        "error" => "Gagal memproses hasil dari AI service.",
                        "raw_response" => $aiResponse,
                        "json_error" => json_last_error_msg()
                    ], 500);
                }

                $parsed["user_latitude"] = $lat ?? "";
                $parsed["user_longitude"] = $lng ?? "";


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

            $response = Http::timeout(5)->post($this->aiUrl . "/ask-gemini", [
                "prompt" => $fullPrompt
            ]);

            Log::info("raw response dari ai service", [
                "response" => $response->body()
            ]);

            if ($response->failed()) {
                throw new Exception("AI service failed (status {$response->status()})");
            }

            $decoded = json_decode($response->body(), true);
            $rawText = $decoded["response"] ?? $response->body();

            $rawText = stripcslashes($rawText);
            $rawText = trim($rawText, "\"");

            return $this->sanitizeJson($rawText);
        }



        private function filterUmkmProducts(array $filters) {
        $query = Product::query()
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
                        $q->where(function($subQuery) use ($word) {
                            $subQuery->where('products.name', 'LIKE', '%' . $word . '%')
                                    ->orWhere('products.description', 'LIKE', '%' . $word . '%');
                        });
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
                Log::info('Final SQL Query', [
        'sql' => $query->toSql(),
        'bindings' => $query->getBindings(),
        'filters' => $filters
    ]);

    $results = $query->get();

    Log::info('Query Results', [
        'count' => $results->count(),
        'data' => $results->toArray()
    ]);

            return $results;
        }

        private function filterUmkms(array $filters){
            $query = Umkm::query();

            if (!empty($filters['category'])) {
                $query->where('type', 'LIKE', '%' . $filters['category'] . '%');
            }

            if (!empty($filters['keywords'])) {
                $query->where(function ($q) use ($filters) {
                    foreach ($filters['keywords'] as $word) {
                        $lowerWord = strtolower($word);
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

        private function sanitizeJson(string $rawText): string
        {

            $cleaned = preg_replace('/^```json\s*|\s*```$/m', '', trim($rawText));


            $cleaned = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $cleaned);

            $cleaned = trim($cleaned);

            return $cleaned;
        }



    }
