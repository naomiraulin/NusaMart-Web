<?php

namespace App\Repositories;

use App\Models\Report;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportRepository
{
    /**
     * Ambil semua laporan (untuk admin).
     */
    public function findAll(array $filters = []): LengthAwarePaginator
    {
        $query = Report::with('reporter');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->orderBy('createAt', 'desc')->paginate(15);
    }

    /**
     * Ambil detail laporan berdasarkan ID.
     */
    public function findById(string $id): ?Report
    {
        return Report::with('reporter')->where('idReport', $id)->first();
    }

    /**
     * Buat laporan baru.
     */
    public function create(array $data): Report
    {
        return Report::create($data);
    }

    /**
     * Update status laporan (oleh admin).
     */
    public function updateStatus(string $id, string $status, ?string $adminNote = null): Report
    {
        $report = Report::where('idReport', $id)->firstOrFail();
        $report->update([
            'status'    => $status,
            'adminNote' => $adminNote,
        ]);

        return $report->fresh();
    }
}