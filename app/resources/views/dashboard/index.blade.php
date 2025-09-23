@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">ダッシュボード</h2>

    {{-- 上段: 円グラフ --}}
    <div class="row mb-5 text-center">
        <div class="col-md-4">
            <canvas id="focusChart" width="150" height="150"></canvas>
            <p class="mt-2">集中度</p>
        </div>
        <div class="col-md-4">
            <canvas id="understandingChart" width="150" height="150"></canvas>
            <p class="mt-2">理解度</p>
        </div>
        <div class="col-md-4">
            <canvas id="motivationChart" width="150" height="150"></canvas>
            <p class="mt-2">モチベーション</p>
        </div>
    </div>

    {{-- 下段: 曜日別積み上げ棒グラフ --}}
    <div class="card">
        <div class="card-header"><strong>曜日別学習時間（教材ごと）</strong></div>
        <div class="card-body">
            <canvas id="weekdayStackedChart" height="200"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- 上段円グラフ ---
    const renderDoughnut = (ctxId, value, color) => {
        new Chart(document.getElementById(ctxId), {
            type: 'doughnut',
            data: {
                labels: ['達成','残り'],
                datasets: [{ data:[value, 5 - value], backgroundColor:[color,'#E0E0E0'] }]
            },
            options: { responsive:true, plugins:{ legend:{display:false}, tooltip:{enabled:false}, title:{display:true,text:value+'/5'} } }
        });
    };
    renderDoughnut('focusChart', {{ $avgFocus }}, 'rgba(54,162,235,0.6)');
    renderDoughnut('understandingChart', {{ $avgUnderstanding }}, 'rgba(75,192,192,0.6)');
    renderDoughnut('motivationChart', {{ $avgMotivation }}, 'rgba(255,159,64,0.6)');

    // --- 下段曜日別積み上げ棒グラフ ---
    const studyTimeByWeekday = @json($studyTimeByWeekday ?? []);
    const materials = @json($materials ?? []);

    const weekdays = Object.keys(studyTimeByWeekday);
    const datasets = materials.map((material, idx) => ({
        label: material,
        data: weekdays.map(day => studyTimeByWeekday[day]?.[material] ?? 0),
        backgroundColor: `hsl(${idx*60},70%,60%)`,
        stack: 'stack1'
    }));

    new Chart(document.getElementById('weekdayStackedChart'), {
        type: 'bar',
        data: { labels: weekdays, datasets: datasets },
        options: {
            responsive:true,
            plugins:{ legend:{position:'top'} },
            scales:{ x:{stacked:true}, y:{ stacked:true, ticks:{ callback: value => Math.round(value/60)+'分' } } }
        }
    });
</script>
@endsection
