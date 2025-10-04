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

    public function exportCsv(Request $request, $reportId)
    {
        $user = auth()->user();

        if (!$user->is_premium) {
            abort(403, 'Premium users only.');
        }

        // レポート取得
        $report = $user->aiReports()->findOrFail($reportId);

        // このレポート期間の学習ログを集計
        $logs = \App\StudyLog::where('user_id', $user->id)
            ->whereBetween('start_time', [$report->period_start, $report->period_end])
            ->get();

        $avgFocus         = $logs->avg('focus_score');
        $avgUnderstanding = $logs->avg('understanding_score');
        $avgMotivation    = $logs->avg('motivation_score');
        $totalMinutes     = intdiv($logs->sum('duration'), 60); // 秒→分換算

        // CSVファイル名
        $fileName = "ai_report_{$report->report_type}_{$report->period_end}.csv";

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($report, $avgFocus, $avgUnderstanding, $avgMotivation, $totalMinutes) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // Excel BOM

            // ヘッダー
            fputcsv($handle, [
                'ID',
                '期間開始日',
                '期間終了日',
                'レポート種別',
                '集中度(平均)',
                '理解度(平均)',
                'モチベーション(平均)',
                '合計学習時間(分)',
                '要約',
                'フィードバック'
            ]);

            // データ
            fputcsv($handle, [
                $report->id,
                $report->period_start,
                $report->period_end,
                $report->report_type,
                $avgFocus ? round($avgFocus, 1) : null,
                $avgUnderstanding ? round($avgUnderstanding, 1) : null,
                $avgMotivation ? round($avgMotivation, 1) : null,
                $totalMinutes,
                preg_replace("/\r|\n/", ' ', $report->summary),
                preg_replace("/\r|\n/", ' ', $report->ai_feedback),
            ]);

            fclose($handle);
        };

        return \Response::stream($callback, 200, $headers);
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
