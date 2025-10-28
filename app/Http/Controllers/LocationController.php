<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



class LocationController extends Controller{

    public $locationApi = "https://nominatim.openstreetmap.org/reverse?";
    public function convertLocation(Request $request): JsonResponse{
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

       $url = $this->locationApi . 'lat=' . $latitude . '&lon=' . $longitude . '&format=json&zoom=18&addressdetails=1';


        try {
            $response = Http::withHeaders([
                'User-Agent' => 'MIA2025-App/1.0'
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json(['location' => $data], 200);
            } else {

                return response()->json(['error' => 'Failed to fetch location data'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Exception occurred'], 500);
        }

    }
}
