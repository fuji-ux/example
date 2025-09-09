@extends('layouts.app')

@section('content')
<div class="container">
    <h1>教材詳細</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $material->name }}</h5>
            <p class="card-text"><strong>カテゴリ:</strong> {{ $material->category }}</p>
            <p class="card-text"><strong>作成日:</strong> {{ $material->created_at }}</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('materials.index') }}" class="btn btn-secondary">戻る</a>
        <a href="{{ route('materials.edit', $material) }}" class="btn btn-warning">編集</a>

        <form action="{{ route('materials.destroy', $material) }}" method="POST" class="d-inline"
              onsubmit="return confirm('本当に削除しますか？');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">削除</button>
        </form>
    </div>
</div>
@endsection
