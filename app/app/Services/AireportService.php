<?php

namespace App\Services;

use App\StudyLog;
use Carbon\Carbon;

function generateSummary($userId, $periodStart, $periodEnd)
{
    // 期間内の学習ログを取得
    $logs = StudyLog::where('user_id', $userId)
        ->whereBetween('start_time', [$periodStart, $periodEnd])
        ->get();

    if ($logs->isEmpty()) {
        return [];
    }

    // 基本統計
    $totalTime = $logs->sum('duration'); // 秒
    $avgTime   = $logs->avg('duration');
    $avgFocus  = $logs->avg('focus_score');
    $avgUnderstanding = $logs->avg('understanding_score');
    $avgMotivation    = $logs->avg('motivation_score');

    // 教材カテゴリ比率
    $categoryRatio = $logs->groupBy('category')->map(function ($items) use ($totalTime) {
        return round(($items->sum('duration') / $totalTime) * 100, 1);
    });

    // 利用教材一覧
    $materialsUsed = $logs->pluck('material_name')->unique()->values();

    // 曜日ごとの傾向
    $byWeekday = [];
    foreach ($logs->groupBy(function ($log) {
        return Carbon::parse($log->start_time)->format('D'); // Mon, Tue...
    }) as $day => $items) {
        $byWeekday[$day] = [
            'total_time' => $items->sum('duration'),
            'avg_focus'  => round($items->avg('focus_score'), 2)
        ];
    }

    // 時間帯ごとの傾向
    $byTimeSlot = [
        'morning'   => ['total_time' => 0, 'focus_sum' => 0, 'count' => 0],
        'afternoon' => ['total_time' => 0, 'focus_sum' => 0, 'count' => 0],
        'evening'   => ['total_time' => 0, 'focus_sum' => 0, 'count' => 0],
        'night'     => ['total_time' => 0, 'focus_sum' => 0, 'count' => 0],
    ];

    foreach ($logs as $log) {
        $hour = Carbon::parse($log->start_time)->hour;

        if ($hour >= 5 && $hour < 12) {
            $slot = 'morning';
        } elseif ($hour >= 12 && $hour < 17) {
            $slot = 'afternoon';
        } elseif ($hour >= 17 && $hour < 21) {
            $slot = 'evening';
        } else {
            $slot = 'night';
        }

        $byTimeSlot[$slot]['total_time'] += $log->duration;
        $byTimeSlot[$slot]['focus_sum']  += $log->focus_score;
        $byTimeSlot[$slot]['count']++;
    }

    // 平均集中度に変換
    $byTimeSlot = collect($byTimeSlot)->map(function ($slot) {
        return [
            'total_time' => $slot['total_time'],
            'avg_focus'  => $slot['count'] > 0
                ? round($slot['focus_sum'] / $slot['count'], 2)
                : 0
        ];
    });

    // JSON summary
    $summary = [
        'total_study_time'   => $totalTime,
        'avg_study_time'     => round($avgTime, 2),
        'avg_focus'          => round($avgFocus, 2),
        'avg_understanding'  => round($avgUnderstanding, 2),
        'avg_motivation'     => round($avgMotivation, 2),
        'category_ratio'     => $categoryRatio,
        'materials_used'     => $materialsUsed,
        'by_weekday'         => $byWeekday,
        'by_time_slot'       => $byTimeSlot,
    ];

    return $summary;
}
