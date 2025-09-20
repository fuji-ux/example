@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">AIレポート詳細</h2>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>期間:</strong> {{ $ai_report->period_start }} ~ {{ $ai_report->period_end }}</p>
            <p><strong>種類:</strong> 
                @if($ai_report->report_type === 'weekly')
                    週次
                @elseif($ai_report->report_type === 'monthly')
                    月次
                @else
                    {{ ucfirst($ai_report->report_type) }}
                @endif
            </p>
            <p><strong>作成日:</strong> {{ $ai_report->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>

<div class="card mb-3">
    <div class="card-header"><strong>概要</strong></div>
    <div class="card-body">

        <!-- 数値概要 -->
        <ul class="list-unstyled mb-4">
            <li><strong>合計学習時間:</strong> {{ $summary['total_duration'] ?? 0 }} 分</li>
            <li><strong>平均集中度:</strong> {{ $summary['avg_focus'] ?? '-' }}</li>
            <li><strong>平均理解度:</strong> {{ $summary['avg_understanding'] ?? '-' }}</li>
        </ul>

        <!-- グラフ -->
        <div class="row">
            <!-- 曜日ごとの学習時間 -->
            <div class="col-md-7 mb-4">
                <canvas id="weekdayChart"></canvas>
            </div>

            <!-- 時間帯別の学習時間 -->
            <div class="col-md-4 mb-4 d-flex justify-content-center">
                <canvas id="timeslotChart"></canvas>
            </div>
        </div>
    </div>
</div>

    <div class="card mb-3">
        <div class="card-header"><strong>AIフィードバック</strong></div>
        <div class="card-body">
            <div class="ai_feedback">
                {!! nl2br(e($ai_report->ai_feedback)) !!}
            </div>
        </div>
    </div>
    <a href="{{ route('ai-reports.export.pdf', $ai_report->id) }}" class="btn btn-primary">PDFエクスポート</a>
    <a href="{{ route('ai-reports.index') }}" class="btn btn-secondary">一覧に戻る</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 曜日ごとの学習時間（棒グラフ）
    var ctx1 = document.getElementById('weekdayChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: @json(array_keys($summary['by_weekday'] ?? [])),
            datasets: [{
                label: '曜日ごとの学習時間（分）',
                data: @json(array_values($summary['by_weekday'] ?? [])),
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });

    // 時間帯別の学習時間（円グラフ）
    var ctx2 = document.getElementById('timeslotChart').getContext('2d');
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: @json(array_keys($summary['by_timeslot'] ?? [])),
            datasets: [{
                label: '時間帯別の学習時間',
                data: @json(array_values($summary['by_timeslot'] ?? [])),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)'
                ]
            }]
        },
        options: { responsive: true }
    });
</script>

@endsection
