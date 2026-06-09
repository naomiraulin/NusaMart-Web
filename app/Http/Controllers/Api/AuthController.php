<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Seller;
use App\Models\Notification;
use App\Services\UserService;
use App\Services\IdGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        private UserService $userService,
        private IdGeneratorService $idGenerator
    ) {}

    // POST /api/auth/register
    public function register(Request $request)
    {
        $request->validate([
            'username'      => 'required|string|unique:users,username',
            'email'         => 'required|email|unique:users,email',
            'phone'         => 'required|string',
            'password'      => 'required|string|min:8',
            'role'          => 'required|in:BUYER,SELLER',
            // Wajib jika role SELLER
            'nik'           => 'required_if:role,SELLER|string|size:16',
            'bankName'      => 'required_if:role,SELLER|string',
            'accountNumber' => 'required_if:role,SELLER|string',
        ]);

        // Buat user baru
        $user = User::create([
            'idUser'   => $this->userService->generateUserId($request->role),
            'username' => $request->username,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
            'imageURL' => null,
            'createAt' => now(),
            'updateAt' => now(),
        ]);

        // Jika SELLER, simpan data seller sekalian
        if ($request->role === 'SELLER') {
            Seller::create([
                'idSeller'      => $user->idUser,
                'nik'           => $request->nik,
                'bankName'      => $request->bankName,
                'accountNumber' => $request->accountNumber,
                'ktpPhoto'      => null,
                'createAt'      => now(),
                'updateAt'      => now(),
            ]);
        }

        // Buat notifikasi welcome
        Notification::create([
            'idNotif'       => $this->idGenerator->generate('NTF', Notification::class, 'idNotif'),
            'idUser'        => $user->idUser,
            'title'         => 'Selamat Datang di NusaMart!',
            'body'          => $request->role === 'SELLER'
                                ? 'Akun seller kamu sudah aktif. Yuk mulai berjualan!'
                                : 'Selamat datang ' . $user->username . '! Yuk mulai belanja.',
            'type'          => 'SISTEM',
            'isRead'        => false,
            'referenceId'   => null,
            'referenceType' => 'SYSTEM',
            'createAt'      => now(),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'token'   => $token,
            'user'    => [
                'idUser'   => $user->idUser,
                'username' => $user->username,
                'email'    => $user->email,
                'role'     => $user->role,
            ]
        ], 201);
    }

    // POST /api/auth/login
    public function login(Request $request)
    {
        $request->validate([
            'emailOrUsername' => 'required|string',
            'password'        => 'required|string',
        ]);

        // Cari user by email atau username
        $user = User::where('email', $request->emailOrUsername)
            ->orWhere('username', $request->emailOrUsername)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Username/email atau password salah.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => [
                'idUser'   => $user->idUser,
                'username' => $user->username,
                'email'    => $user->email,
                'role'     => $user->role,
                'imageURL' => $user->imageURL,
            ]
        ]);
    }

    // POST /api/auth/logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}