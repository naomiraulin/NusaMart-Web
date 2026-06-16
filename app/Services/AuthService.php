<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\SellerRepository;
use App\Repositories\StoreRepository;
use App\Repositories\WalletRepository;
use App\Models\Store;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private SellerRepository   $sellerRepository,
        private StoreRepository    $storeRepository,
        private WalletRepository   $walletRepository,
        private IdGeneratorService $idGenerator,
        private UserService        $userService,
    ) {}

    /**
     * Register user baru sebagai BUYER.
     */
    public function registerBuyer(array $data): array
    {
        $user = $this->userService->createUser([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'phone'    => $data['phone'] ?? null,
            'role'     => 'BUYER',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Register user baru sebagai SELLER.
     * Otomatis buat data Seller + Store + Wallet.
     */
    public function registerSeller(array $data): array
    {
        // 1. Buat user dengan role SELLER via UserService
        $user = $this->userService->createUser([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'phone'    => $data['phone'] ?? null,
            'role'     => 'SELLER',
        ]);

        // 2. Buat data seller (NIK, bank, rekening)
        $seller = $this->sellerRepository->create([
            'idSeller'      => $user->idUser,
            'nik'           => $data['nik'],
            'bankName'      => $data['bank_name'],
            'accountNumber' => $data['account_number'],
        ]);

        // 3. Buat store — generate ID dengan 3 parameter sesuai IdGeneratorService
        $store = $this->storeRepository->create([
            'idStore'  => $this->idGenerator->generate('STR', Store::class, 'idStore'),
            'idSeller' => $seller->idSeller,
            'name'     => $data['store_name'],
            'location' => $data['location'],
            'isActive' => true,
        ]);

        // 4. Buat wallet untuk store
        $this->walletRepository->create($store->idStore);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'store' => $store,
            'token' => $token,
        ];
    }

    /**
     * Login user.
     */
    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        // Hapus token lama supaya tidak numpuk
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Logout user — hapus token yang sedang aktif.
     */
    public function logout(User $user): void
    {
        if ($user->currentAccessToken()) {
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        }
    }

    /**
     * Update profil user.
     */
    public function updateProfile(string $userId, array $data): User
    {
        $updateData = [];

        if (!empty($data['username'])) {
            $updateData['username'] = $data['username'];
        }

        if (!empty($data['phone'])) {
            $updateData['phone'] = $data['phone'];
        }

        if (!empty($data['image_url'])) {
            $updateData['imageURL'] = $data['image_url'];
        }

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $updateData['updateAt'] = now();

        $user = User::findOrFail($userId);
        $user->update($updateData);

        return $user->fresh();
    }
}