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

        // 今週のスコア（例: 平均値）
        $logs = $user->studyLogs()
            ->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()])
            ->get();

        $avgFocus = round($logs->avg('focus_score'), 1) ?? 0;
        $avgUnderstanding = round($logs->avg('understanding_score'), 1) ?? 0;
        $avgMotivation = round($logs->avg('motivation_score'), 1) ?? 0;

        // 曜日別の学習時間 × 教材ごと
        $studyTimeByWeekday = [];
        $materials = $user->materials()->pluck('name')->toArray();

        foreach ($logs as $log) {
            $day = $log->start_time->format('D'); // "Mon", "Tue", ...
            $material = $log->material_name;
            $studyTimeByWeekday[$day][$material] = ($studyTimeByWeekday[$day][$material] ?? 0) + $log->duration;
        }

        return view('dashboard.index', compact(
            'avgFocus',
            'avgUnderstanding',
            'avgMotivation',
            'studyTimeByWeekday',
            'materials'
        ));
    }
}
