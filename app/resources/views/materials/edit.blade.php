@extends('layouts.app')

@section('content')
<div class="container">
    <h1>教材の編集</h1>

    <form action="{{ route('materials.update', $material) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>教材名</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $material->name) }}" required>
        </div>

        <div class="mb-3">
            <label>カテゴリ</label>
            <input type="text" name="category" class="form-control" value="{{ old('category', $material->category) }}" required>
        </div>

        <button type="submit" class="btn btn-success">更新</button>
        <a href="{{ route('materials.index') }}" class="btn btn-secondary">戻る</a>
    </form>
</div>
@endsection
