<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function show(string $id)
    {
        $user = User::where('idUser', $id)->firstOrFail();

        return response()->json([
            'idUser'   => $user->idUser,
            'username' => $user->username,
            'role'     => $user->role,
            'imageURL' => $user->imageURL,
            'createAt' => $user->createAt,
        ]);
    }

    // GET /api/user/profile
    public function profile(Request $request)
    {
        $user = $request->user();

        $data = [
            'idUser'   => $user->idUser,
            'username' => $user->username,
            'email'    => $user->email,
            'phone'    => $user->phone,
            'role'     => $user->role,
            'imageURL' => $user->imageURL,
            'createAt' => $user->createAt,
        ];

        // Kalau SELLER, tambahkan data seller
        if ($user->role === 'SELLER') {
            $seller = $user->seller;
            $data['seller'] = $seller ? [
                'nik'           => $seller->nik,
                'bankName'      => $seller->bankName,
                'accountNumber' => $seller->accountNumber,
            ] : null;
        }

        return response()->json($data);
    }

    // PUT /api/user/profile
    public function update(Request $request)
    {
        $request->validate([
            'username' => 'sometimes|string|unique:users,username,' . $request->user()->idUser . ',idUser',
            'phone'    => 'sometimes|string',
            'imageURL' => 'sometimes|nullable|string',
        ]);

        $user = $request->user();
        $user->update([
            'username' => $request->username ?? $user->username,
            'phone'    => $request->phone ?? $user->phone,
            'imageURL' => $request->imageURL ?? $user->imageURL,
            'updateAt' => now(),
        ]);

        return response()->json([
            'message' => 'Profil berhasil diupdate',
            'user'    => [
                'idUser'   => $user->idUser,
                'username' => $user->username,
                'email'    => $user->email,
                'phone'    => $user->phone,
                'role'     => $user->role,
                'imageURL' => $user->imageURL,
            ]
        ]);
    }
}