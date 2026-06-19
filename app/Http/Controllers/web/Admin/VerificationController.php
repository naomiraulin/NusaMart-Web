<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Verification\RejectVerificationRequest;
use App\Repositories\VerificationRepository;
use App\Services\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function __construct(
        private VerificationRepository $verificationRepository,
        private VerificationService $verificationService,
    ) {}

    public function index(Request $request): View
    {
        $verification = $this->verificationRepository->getAllPaginated(15, $request->input('status'));
        return view('admin.verification.index', compact('verification'));
    }

    public function show(string $id): View
    {
        $verification = $this->verificationRepository->findById($id);
        return view('admin.verification.show', compact('verification'));
    }

    public function approve(Request $request, string $id): RedirectResponse
    {
        $this->verificationService->approve($id, $request->input('notes'));
        return redirect()->route('admin.verification.index')
            ->with('success', 'Verifikasi toko berhasil disetujui.');
    }

    public function reject(RejectVerificationRequest $request, string $id): RedirectResponse
    {
        $this->verificationService->reject($id, $request->validated('notes'));
        return redirect()->route('admin.verification.index')
            ->with('success', 'Verifikasi toko berhasil ditolak.');
    }
}