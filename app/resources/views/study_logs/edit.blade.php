@extends('layouts.app')

@section('content')
<div class="container">
    <h1>学習記録 編集</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('study_logs.update', $study_log) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>教材</label>
            <select name="material_id" class="form-control" required>
                @foreach ($materials as $material)
                    <option value="{{ $material->id }}" 
                        {{ $study_log->material_id == $material->id ? 'selected' : '' }}>
                        {{ $material->name }} ({{ $material->category }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>開始時間</label>
            <input type="datetime-local" name="start_time" class="form-control"
                   value="{{ old('start_time', $study_log->start_time->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div class="mb-3">
            <label>終了時間</label>
            <input type="datetime-local" name="end_time" class="form-control"
                   value="{{ old('end_time', $study_log->end_time->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div class="mb-3">
            <label>集中度</label>
            <input type="number" name="focus_score" class="form-control" 
                   value="{{ old('focus_score', $study_log->focus_score) }}" min="1" max="5" required>
        </div>

        <div class="mb-3">
            <label>理解度</label>
            <input type="number" name="understanding_score" class="form-control" 
                   value="{{ old('understanding_score', $study_log->understanding_score) }}" min="1" max="5" required>
        </div>

        <div class="mb-3">
            <label>モチベーション</label>
            <input type="number" name="motivation_score" class="form-control" 
                   value="{{ old('motivation_score', $study_log->motivation_score) }}" min="1" max="5" required>
        </div>

        <div class="mb-3">
            <label>メモ</label>
            <textarea name="comment" class="form-control">{{ old('comment', $study_log->comment) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">更新</button>
        <a href="{{ route('study_logs.index') }}" class="btn btn-secondary">戻る</a>
    </form>
</div>
@endsection
