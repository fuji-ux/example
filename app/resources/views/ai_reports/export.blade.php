<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
<style>
@font-face {
    font-family: 'NotoSansJP';
    font-style: normal;
    font-weight: 400;
    src: url("{{ storage_path('fonts/NotoSansJP-Regular.ttf') }}") format('truetype');
}
@font-face {
    font-family: 'NotoSansJP';
    font-style: normal;
    font-weight: 700;
    src: url("{{ storage_path('fonts/NotoSansJP-Bold.ttf') }}") format('truetype');
}

body, h1, h2, h3, p, ul, li {
    font-family: 'NotoSansJP', sans-serif !important;
}
</style>
</head>

<body>
    <h1>AIレポート ({{ $report->report_type }})</h1>
    <p>期間: {{ $report->period_start }} - {{ $report->period_end }}</p>

    <h2>統計情報</h2>
    <ul>
        <li>合計学習時間: {{ $summary['total_duration'] }} 分</li>
        <li>平均集中度: {{ $summary['avg_focus'] }}</li>
        <li>平均理解度: {{ $summary['avg_understanding'] }}</li>
    </ul>

    <h2>AIフィードバック</h2>
    <div>{!! nl2br(e($report->ai_feedback)) !!}</div>
</body>

</html>