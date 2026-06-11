<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\IdGeneratorService;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function __construct(
        private IdGeneratorService $idGenerator
    ) {}

    // GET /api/admin/reports → semua laporan (Admin)
    public function index(Request $request)
    {
        $query = Report::orderByDesc('createAt');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        return response()->json($query->get());
    }

    // GET /api/admin/reports/{id} → detail laporan
    public function show(string $id)
    {
        $report = Report::where('idReport', $id)->firstOrFail();
        return response()->json($report);
    }

    // PUT /api/admin/reports/{id}/status → update status laporan
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status'    => 'required|in:OPEN,REVIEWED,RESOLVED,DISMISSED',
            'adminNote' => 'sometimes|nullable|string',
        ]);

        $report = Report::where('idReport', $id)->firstOrFail();

        $report->update([
            'status'    => $request->status,
            'adminNote' => $request->adminNote ?? $report->adminNote,
            'updateAt'  => now(),
        ]);

        return response()->json([
            'message' => 'Status laporan berhasil diupdate',
            'report'  => $report,
        ]);
    }
}