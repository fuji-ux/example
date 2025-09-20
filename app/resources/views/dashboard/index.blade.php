{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">ダッシュボード</h1>

    {{-- ✅ 統計カード --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="p-4 bg-white rounded-lg shadow">
            <p class="text-gray-500">今週の学習時間</p>
            <p class="text-2xl font-bold">{{ $summary['total_duration_week'] }} 分</p>
        </div>
        <div class="p-4 bg-white rounded-lg shadow">
            <p class="text-gray-500">今月の学習時間</p>
            <p class="text-2xl font-bold">{{ $summary['total_duration_month'] }} 分</p>
        </div>
        <div class="p-4 bg-white rounded-lg shadow">
            <p class="text-gray-500">平均集中度</p>
            <p class="text-2xl font-bold">{{ $summary['avg_focus'] }}</p>
        </div>
        <div class="p-4 bg-white rounded-lg shadow">
            <p class="text-gray-500">平均理解度</p>
            <p class="text-2xl font-bold">{{ $summary['avg_understanding'] }}</p>
        </div>
    </div>

    {{-- ✅ グラフ --}}
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h2 class="text-lg font-semibold mb-4">学習時間の推移</h2>
        <canvas id="studyChart" height="120"></canvas>
    </div>

{{-- ✅ Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('studyChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($recentLogs->pluck('date')->reverse()) !!},
            datasets: [{
                label: '学習時間 (分)',
                data: {!! json_encode($recentLogs->pluck('duration')->reverse()) !!},
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
