<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class IdGeneratorService
{
    // Menyimpan counter sementara di memory selama proses seeding
    private static array $counters = [];

    public function generate(string $prefix, string $model, string $column): string
    {
        // Ambil nilai terbesar antara DB dan counter in-memory
        $lastFromDb = $model::where($column, 'like', $prefix . '-%')
            ->orderByDesc($column)
            ->value($column);

        $lastNumberFromDb = $lastFromDb
            ? (int) substr($lastFromDb, strlen($prefix) + 1)
            : 0;

        // Bandingkan dengan counter in-memory
        $lastNumberFromMemory = self::$counters[$prefix] ?? 0;

        // Pakai yang terbesar
        $nextNumber = max($lastNumberFromDb, $lastNumberFromMemory) + 1;

        // Update counter in-memory
        self::$counters[$prefix] = $nextNumber;

        return $prefix . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    // Reset counter (opsional, berguna saat testing)
    public static function resetCounters(): void
    {
        self::$counters = [];
    }
}