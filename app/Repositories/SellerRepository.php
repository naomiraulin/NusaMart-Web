<?php

namespace App\Repositories;

use App\Models\Seller;

class SellerRepository
{
    /**
     * Ambil data seller berdasarkan ID seller.
     */
    public function findById(string $id): ?Seller
    {
        return Seller::with('user')->where('idSeller', $id)->first();
    }

    /**
     * Ambil data seller berdasarkan idUser.
     */
    public function findByUser(string $userId): ?Seller
    {
        return Seller::with('user')->where('idSeller', $userId)->first();
    }

    /**
     * Daftarkan user sebagai seller baru.
     */
    public function create(array $data): Seller
    {
        return Seller::create($data);
    }

    /**
     * Update data seller (NIK, bank, nomor rekening).
     */
    public function update(string $id, array $data): Seller
    {
        $seller = Seller::where('idSeller', $id)->firstOrFail();
        $seller->update($data);

        return $seller->fresh();
    }
}