@extends('layouts.app')

@section('content')
<div class="container">
    <h1>学習記録の追加</h1>

    <form action="{{ route('study_logs.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>教材</label>
            <select name="material_id" class="form-control" required>
                <option value="">選択してください</option>
                @foreach($materials as $material)
                    <option value="{{ $material->id }}">{{ $material->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>カテゴリ</label>
            <input type="text" name="category" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>開始時間</label>
            <input type="datetime-local" name="start_time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>終了時間</label>
            <input type="datetime-local" name="end_time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>集中度 (1-5)</label>
            <input type="number" name="focus_score" class="form-control" min="1" max="5">
        </div>

        <div class="mb-3">
            <label>理解度 (1-5)</label>
            <input type="number" name="understanding_score" class="form-control" min="1" max="5">
        </div>

        <div class="mb-3">
            <label>モチベーション (1-5)</label>
            <input type="number" name="motivation_score" class="form-control" min="1" max="5">
        </div>

        <div class="mb-3">
            <label>メモ</label>
            <textarea name="comment" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">登録</button>
    </form>
</div>
@endsection
