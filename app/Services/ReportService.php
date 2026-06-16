<?php

namespace App\Services;

use App\Models\Report;
use App\Repositories\ReportRepository;
use App\Services\IdGeneratorService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class ReportService
{
    public function __construct(
        private ReportRepository   $reportRepository,
        private IdGeneratorService $idGenerator,
    ) {}

    /**
     * Ambil semua laporan (untuk admin).
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->reportRepository->findAll($filters);
    }

    /**
     * Ambil detail laporan.
     */
    public function getById(string $id): Report
    {
        $report = $this->reportRepository->findById($id);

        if (!$report) {
            abort(404, 'Laporan tidak ditemukan.');
        }

        return $report;
    }

    /**
     * Buat laporan baru.
     * User tidak bisa melaporkan dirinya sendiri.
     */
    public function create(string $reporterId, array $data): Report
    {
        if ($data['type'] === 'user' && $data['reference_id'] === $reporterId) {
            throw ValidationException::withMessages([
                'reference_id' => ['Kamu tidak bisa melaporkan dirimu sendiri.'],
            ]);
        }

        return $this->reportRepository->create([
            'idReport'    => $this->idGenerator->generate('RPT', Report::class, 'idReport'),
            'reporterId'  => $reporterId,
            'type'        => $data['type'],
            'referenceId' => $data['reference_id'] ?? null,
            'reason'      => $data['reason'],
            'status'      => 'OPEN',
        ]);
    }

    /**
     * Review laporan oleh admin — ubah status & tambah catatan.
     */
    public function review(string $reportId, string $status, ?string $adminNote = null): Report
    {
        $allowed = ['REVIEWED', 'RESOLVED', 'DISMISSED'];

        if (!in_array($status, $allowed)) {
            throw ValidationException::withMessages([
                'status' => ['Status laporan tidak valid.'],
            ]);
        }

        return $this->reportRepository->updateStatus($reportId, $status, $adminNote);
    }
}