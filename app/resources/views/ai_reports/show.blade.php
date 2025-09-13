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
            <div>{{ $ai_report->summary }}</div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header"><strong>AIフィードバック</strong></div>
        <div class="card-body">
            <p>{{ $ai_report->ai_feedback }}</p>
        </div>
    </div>

    <a href="{{ route('ai-reports.index') }}" class="btn btn-secondary">一覧に戻る</a>
</div>
@endsection
