@extends('layouts.app')

@section('content')
<div class="container">
    <h1>教材の追加</h1>

    <form action="{{ route('materials.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>教材名</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>カテゴリ</label>
            <input type="text" name="category" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">登録</button>
    </form>
</div>
@endsection
