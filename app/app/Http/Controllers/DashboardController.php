<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StudyLog;
use App\StudySchedule;
use App\Badge;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 今週・今月の学習時間
        $totalDurationWeek = \App\StudyLog::where('user_id', $user->id)
            ->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('duration');

        $totalDurationMonth = \App\StudyLog::where('user_id', $user->id)
            ->whereMonth('start_time', now()->month)
            ->sum('duration');

        // 平均集中度・理解度
        $avgFocus = \App\StudyLog::where('user_id', $user->id)->avg('focus_score');
        $avgUnderstanding = \App\StudyLog::where('user_id', $user->id)->avg('understanding_score');

        // 直近5件の学習ログ
        $recentLogs = \App\StudyLog::where('user_id', $user->id)
            ->orderBy('start_time', 'desc')
            ->take(5)
            ->get()
            ->map(function ($log) {
                return [
                    'date' => \Carbon\Carbon::parse($log->start_time)->format('Y-m-d'),
                    'duration' => $log->duration,
                ];
            });

        // ダッシュボードに渡すデータ
        $summary = [
            'total_duration_week' => round($totalDurationWeek / 60 ,1),
            'total_duration_month' => round($totalDurationMonth / 60 ,1),
            'avg_focus' => round($avgFocus, 2),
            'avg_understanding' => round($avgUnderstanding, 2),
        ];

        return view('dashboard.index', compact('user', 'summary', 'recentLogs'));
    }
}
