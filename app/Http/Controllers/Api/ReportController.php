<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\IdGeneratorService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private IdGeneratorService $idGenerator
    ) {}

    // GET /api/reports → laporan milik user
    public function index(Request $request)
    {
        $reports = Report::where('reporterId', $request->user()->idUser)
            ->orderByDesc('createAt')
            ->get();

        return response()->json($reports);
    }

    // GET /api/reports/{id} → detail laporan milik user
    public function show(Request $request, string $id)
    {
        $report = Report::where('idReport', $id)
            ->where('reporterId', $request->user()->idUser)
            ->firstOrFail();

        return response()->json($report);
    }

    // POST /api/reports → buat laporan baru
    public function store(Request $request)
    {
        $request->validate([
            'type'        => 'required|in:product,review,user,others',
            'referenceId' => 'required_unless:type,others|nullable|string',
            'reason'      => 'required|string',
        ]);

        // Pastikan ada target laporan kecuali type others
        if ($request->type !== 'others' && !$request->referenceId) {
            return response()->json([
                'message' => 'Harus ada target laporan.'
            ], 422);
        }

        $report = Report::create([
            'idReport'    => $this->idGenerator->generate('RPT', Report::class, 'idReport'),
            'reporterId'  => $request->user()->idUser,
            'type'        => $request->type,
            'referenceId' => $request->referenceId,
            'reason'      => $request->reason,
            'status'      => 'OPEN',
            'adminNote'   => null,
            'createAt'    => now(),
            'updateAt'    => now(),
        ]);

        return response()->json([
            'message' => 'Laporan berhasil dikirim',
            'report'  => $report,
        ], 201);
    }
}