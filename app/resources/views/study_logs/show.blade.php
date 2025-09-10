@extends('layouts.app')

@section('content')
<div class="container">
    <h1>学習記録 詳細</h1>

    <table class="table">
        <tr><th>教材</th><td>{{ $study_log->material_name }}</td></tr>
        <tr><th>カテゴリ</th><td>{{ $study_log->category }}</td></tr>
        <tr><th>開始時間</th><td>{{ $study_log->start_time }}</td></tr>
        <tr><th>終了時間</th><td>{{ $study_log->end_time }}</td></tr>
        <tr><th>学習時間(秒)</th><td>{{ $study_log->duration }}</td></tr>
        <tr><th>集中度</th><td>{{ $study_log->focus_score }}</td></tr>
        <tr><th>理解度</th><td>{{ $study_log->understanding_score }}</td></tr>
        <tr><th>モチベーション</th><td>{{ $study_log->motivation_score }}</td></tr>
        <tr><th>メモ</th><td>{{ $study_log->comment }}</td></tr>
    </table>

    <a href="{{ route('study_logs.index') }}" class="btn btn-secondary">戻る</a>
    <a href="{{ route('study_logs.edit', $study_log) }}" class="btn btn-warning">編集</a>
</div>
@endsection
