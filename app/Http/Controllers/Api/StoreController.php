<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\BadgeVerification;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    // GET /api/stores → list semua toko
    public function index()
    {   
        $stores = Store::all()->map(function ($store) {
            // Cek apakah toko ini adalah STR-000001 dan punya badge APPROVED
            $isVerified = BadgeVerification::where('idStore', $store->idStore)
                ->where('badgeType', 'LOCAL')
                ->where('status', 'APPROVED')
                ->exists();
            
            // Ubah ke array agar properti isVerified PASTI ter-render di JSON
            $storeData = $store->toArray();
            $storeData['isVerified'] = $isVerified;
            
            return $storeData;
        });

        return response()->json($stores);
    }

    // GET /api/stores/{id}
    public function show(string $id)
    {
        $store = Store::where('idStore', $id)->firstOrFail();

        $isVerified = BadgeVerification::where('idStore', $store->idStore)
            ->where('badgeType', 'LOCAL')
            ->where('status', 'APPROVED')
            ->exists();

        $storeData = $store->toArray();
        $storeData['isVerified'] = $isVerified;

        return response()->json($storeData);
    }

    // GET /api/seller/store → ambil toko milik seller yang login
    public function myStore(Request $request)
    {
        $store = Store::where('idSeller', $request->user()->idUser)->first();

        if (!$store) {
            return response()->json(['message' => 'Toko belum dibuat'], 404);
        }

        $isVerified = BadgeVerification::where('idStore', $store->idStore)
            ->where('badgeType', 'LOCAL')
            ->where('status', 'APPROVED')
            ->exists();

        $storeData = $store->toArray();
        $storeData['isVerified'] = $isVerified;

        return response()->json($storeData);
    }

    // PUT /api/seller/store → update toko milik seller
    public function update(Request $request)
    {
        $request->validate([
            'name'        => 'sometimes|string',
            'description' => 'sometimes|string',
            'logoURL'     => 'sometimes|nullable|string',
            'location'    => 'sometimes|string',
            'urlLocation' => 'sometimes|nullable|string',
        ]);

        $store = Store::where('idSeller', $request->user()->idUser)->firstOrFail();

        $store->update([
            'name'        => $request->name        ?? $store->name,
            'description' => $request->description ?? $store->description,
            'logoURL'     => $request->logoURL     ?? $store->logoURL,
            'location'    => $request->location    ?? $store->location,
            'urlLocation' => $request->urlLocation ?? $store->urlLocation,
            'updateAt'    => now(),
        ]);

        $isVerified = BadgeVerification::where('idStore', $store->idStore)
            ->where('badgeType', 'LOCAL')
            ->where('status', 'APPROVED')
            ->exists();

        $storeData = $store->toArray();
        $storeData['isVerified'] = $isVerified;

        return response()->json([
            'message' => 'Toko berhasil diupdate',
            'store'   => $storeData,
        ]);
    }
}
