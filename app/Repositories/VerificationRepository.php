<?php

namespace App\Repositories;

use App\Models\BadgeVerification;
use Illuminate\Pagination\LengthAwarePaginator;

class VerificationRepository
{
    public function getAllPaginated(int $perPage = 15, ?string $status = null): LengthAwarePaginator
    {
        $query = BadgeVerification::with(['store.seller.user']);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest('requestDate')->paginate($perPage);
    }

    public function findById(string $id): BadgeVerification
    {
        return BadgeVerification::with(['store.seller.user'])
            ->where('idBadge', $id)
            ->firstOrFail();
    }

    public function update(BadgeVerification $verification, array $data): bool
    {
        return $verification->update($data);
    }
}