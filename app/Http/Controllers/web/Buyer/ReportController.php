<?php

namespace App\Http\Controllers\Web\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\StoreReportRequest;
use App\Services\ReportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService,
    ) {}

    /**
     * Form buat laporan/keluhan.
     */
    public function create(): View
    {
        return view('buyer.reports.create');
    }

    /**
     * Simpan laporan baru.
     */
    public function store(StoreReportRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $this->reportService->create($user->idUser, $request->validated());

        return back()->with('success', 'Laporan berhasil dikirim. Tim kami akan segera menindaklanjuti.');
    }
}