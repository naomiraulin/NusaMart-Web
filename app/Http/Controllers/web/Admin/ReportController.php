<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService,
    ) {}

    /**
     * Daftar semua laporan.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['status', 'type']);
        $reports = $this->reportService->getAll($filters);

        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Detail laporan.
     */
    public function show(string $id): View
    {
        $report = $this->reportService->getById($id);

        return view('admin.reports.show', compact('report'));
    }

    /**
     * Update status laporan.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'status'     => ['required', 'in:REVIEWED,RESOLVED,DISMISSED'],
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        $this->reportService->review(
            $id,
            $request->input('status'),
            $request->input('admin_note'),
        );

        return redirect()->route('admin.reports.index')
            ->with('success', 'Laporan berhasil diperbarui.');
    }
}