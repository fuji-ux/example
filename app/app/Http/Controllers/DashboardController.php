<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudyLog;
use App\Models\StudySchedule;
use App\Models\Badge;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 今日の学習時間
        $todaySeconds = StudyLog::where('user_id', $user->id)
            ->whereDate('start_time', today())
            ->sum('duration');

        // 直近の学習ログ
        $recentLogs = StudyLog::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // 今週のスケジュール
        $schedules = StudySchedule::where('user_id', $user->id)
            ->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()])
            ->get();

        // バッジ
        $badges = Badge::where('user_id', $user->id)->get();

        return view('dashboard.index', compact(
            'user', 'todaySeconds', 'recentLogs', 'schedules', 'badges'
        ));
    }
}