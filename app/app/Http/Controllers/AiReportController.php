<?php

namespace App\Http\Controllers;

use App\AiReport;
use Illuminate\Http\Request;
use PDF; // barryvdh/laravel-dompdf

class AiReportController extends Controller
{
    // 一覧表示
    public function index()
    {
        $reports = auth()->user()->aiReports()
            ->orderBy('period_start', 'desc')
            ->get();

        $weeklyReports = AiReport::where('report_type', 'weekly')
        ->where('user_id', auth()->id())
        ->latest()->get();

        return view('ai_reports.index', compact('reports', 'weeklyReports'));
    }

    // 詳細表示
    public function show(AiReport $ai_report)
    {
        if ($ai_report->user_id !== auth()->id()) {
            dd(auth()->id(), $ai_report->user_id);
            abort(403, 'このレポートにはアクセスできません');
        }

        $summary = json_decode($ai_report->summary, true);

        return view('ai_reports.show', compact('summary', 'ai_report'));
    }

    public function exportPdf($id)
    {
        $report = AiReport::findOrFail($id);
        $summary = json_decode($report->summary, true);

        $pdf = PDF::loadView('ai_reports.export', [
            'report' => $report,
            'summary' => $summary,
        ]);

        return $pdf->download("ai_report_{$report->id}.pdf");
    }

public function list($type)
{
    $user = auth()->user();

    if ($type === 'weekly') {
        $reports = AIReport::where('user_id', $user->id)
            ->where('report_type', 'weekly')
            ->orderBy('period_start', 'desc')
            ->get();
    } elseif ($type === 'monthly') {
        $reports = AIReport::where('user_id', $user->id)
            ->where('report_type', 'monthly')
            ->orderBy('period_start', 'desc')
            ->get();
    } else {
        $reports = collect(); // 万が一の空データ
    }

    return view('ai_reports.partials.list', compact('reports'));
}
}
