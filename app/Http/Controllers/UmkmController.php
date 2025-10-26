<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UmkmController extends Controller{
    public function insertUmkm(Request $request): JsonResponse{
        $umkm = new Umkm();
        $umkm->type = $request->input('type');
        $umkm->name = $request->input('name');
        $umkm->photoUrl = $request->input('photoUrl');
        $umkm->description = $request->input('description');
        $umkm->latitude = $request->input('latitude');
        $umkm->longitude = $request->input('longitude');
        $umkm->userId = $request->input('userId');
        $umkm->save();

        return response()->json(['message' => 'UMKM inserted successfully'], 200);
    }

    public function getAllUmkm(Request $request): JsonResponse{
        $umkm = Umkm::all();
        return response()->json($umkm, 200);
    }

    public function getUmkmById(Request $request, $id): JsonResponse{
        $umkm = Umkm::find($id);
        if ($umkm) {
            return response()->json($umkm, 200);
        } else {
            return response()->json(['message' => 'UMKM not found'], 404);
        }
    }

    public function getUmkmByType($type): JsonResponse{
        $umkm = Umkm::where('type', '=', $type)->get();
        if ($umkm) {
            return response()->json($umkm, 200);
        } else {
            return response()->json(['message' => 'UMKM not found'], 404);
        }
    }

    public function getRandomUmkm(Request $request): JsonResponse{
        $umkm = Umkm::inRandomOrder()->limit(10)->get();
        return response()->json($umkm, 200);
    }
}
