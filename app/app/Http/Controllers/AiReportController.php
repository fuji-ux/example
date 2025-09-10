<?php

namespace App\Http\Controllers;

use App\AiReport;
use Illuminate\Http\Request;

class AiReportController extends Controller
{
    // 一覧表示
    public function index()
    {
        $reports = auth()->user()->aiReports()
            ->orderBy('period_start', 'desc')
            ->get();

        return view('ai_reports.index', compact('reports'));
    }

    // 詳細表示
    public function show(AiReport $ai_report)
    {
        if ($ai_report->user_id !== auth()->id()) {
            dd(auth()->id(), $ai_report->user_id);
            abort(403, 'このレポートにはアクセスできません');
        }

        // 変数名を修正：`$ai_report`をビューに渡します
        return view('ai_reports.show', compact('ai_report'));
    }
}