<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Services\IdGeneratorService;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    public function __construct(
        private IdGeneratorService $idGenerator
    ) {}

    // GET /api/user/addresses
    public function index(Request $request)
    {
        $addresses = UserAddress::where('idUser', $request->user()->idUser)->get();
        return response()->json($addresses);
    }

    // POST /api/user/addresses
    public function store(Request $request)
    {
        $request->validate([
            'label'           => 'required|string',
            'receiver'        => 'required|string',
            'phone'           => 'required|string',
            'completeAddress' => 'required|string',
            'city'            => 'required|string',
            'province'        => 'required|string',
            'postalCode'      => 'required|string',
            'isDefault'       => 'boolean',
        ]);

        $userId = $request->user()->idUser;

        // Kalau isDefault true, reset semua alamat lain
        if ($request->isDefault) {
            UserAddress::where('idUser', $userId)->update(['isDefault' => false]);
        }

        $address = UserAddress::create([
            'idAddress'       => $this->idGenerator->generate('ADR', UserAddress::class, 'idAddress'),
            'idUser'          => $userId,
            'label'           => $request->label,
            'receiver'        => $request->receiver,
            'phone'           => $request->phone,
            'completeAddress' => $request->completeAddress,
            'city'            => $request->city,
            'province'        => $request->province,
            'postalCode'      => $request->postalCode,
            'isDefault'       => $request->isDefault ?? false,
        ]);

        return response()->json([
            'message' => 'Alamat berhasil ditambahkan',
            'address' => $address,
        ], 201);
    }

    // PUT /api/user/addresses/{id}
    public function update(Request $request, string $id)
    {
        $address = UserAddress::where('idAddress', $id)
            ->where('idUser', $request->user()->idUser)
            ->firstOrFail();

        $request->validate([
            'label'           => 'sometimes|string',
            'receiver'        => 'sometimes|string',
            'phone'           => 'sometimes|string',
            'completeAddress' => 'sometimes|string',
            'city'            => 'sometimes|string',
            'province'        => 'sometimes|string',
            'postalCode'      => 'sometimes|string',
            'isDefault'       => 'boolean',
        ]);

        $userId = $request->user()->idUser;

        if ($request->isDefault) {
            UserAddress::where('idUser', $userId)
                ->where('idAddress', '!=', $id)
                ->update(['isDefault' => false]);
        }

        $address->update($request->only([
            'label', 'receiver', 'phone',
            'completeAddress', 'city', 'province',
            'postalCode', 'isDefault'
        ]));

        return response()->json([
            'message' => 'Alamat berhasil diupdate',
            'address' => $address,
        ]);
    }

    // DELETE /api/user/addresses/{id}
    public function destroy(Request $request, string $id)
    {
        $address = UserAddress::where('idAddress', $id)
            ->where('idUser', $request->user()->idUser)
            ->firstOrFail();

        $address->delete();

        return response()->json([
            'message' => 'Alamat berhasil dihapus'
        ]);
    }

    // PUT /api/user/addresses/{id}/default
    public function setDefault(Request $request, string $id)
    {
        $userId = $request->user()->idUser;

        UserAddress::where('idUser', $userId)->update(['isDefault' => false]);

        $address = UserAddress::where('idAddress', $id)
            ->where('idUser', $userId)
            ->firstOrFail();

        $address->update(['isDefault' => true]);

        return response()->json([
            'message' => 'Alamat utama berhasil diubah',
            'address' => $address,
        ]);
    }

    // GET /api/user/{id}
    public function show($id)
    {
        // Pastikan model User sudah di-import: use App\Models\User;
        // Dan pastikan relasi 'seller' sudah didefinisikan di model User
        $user = \App\Models\User::where('idUser', $id)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $data = [
            'idUser'   => $user->idUser,
            'username' => $user->username,
            'email'    => $user->email,
            'phone'    => $user->phone,
            'role'     => $user->role,
            'imageURL' => $user->imageURL,
            'createAt' => $user->createAt,
        ];

        if ($user->role === 'SELLER') {
            $seller = $user->seller; // Memanggil relasi
            $data['seller'] = $seller ? [
                'nik'           => $seller->nik,
                'bankName'      => $seller->bankName,
                'accountNumber' => $seller->accountNumber,
            ] : null;
        }

        return response()->json($data);
    }
}
