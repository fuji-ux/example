@extends('layouts.app')

@section('content')
<div class="container">
    <h1>教材一覧</h1>
    <a href="{{ route('materials.create') }}" class="btn btn-primary mb-3">新規追加</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>教材名</th>
                <th>カテゴリ</th>
                <th>作成日</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials as $material)
            <tr>
                <td>{{ $material->name }}</td>
                <td>{{ $material->category }}</td>
                <td>{{ $material->created_at }}</td>
                <td>
                    <a href="{{ route('materials.show', $material) }}" class="btn btn-sm btn-info">詳細</a>
                    <form action="{{ route('materials.destroy', $material) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('本当に削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $materials->links() }}
</div>
@endsection
