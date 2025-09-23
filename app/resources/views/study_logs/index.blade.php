@extends('layouts.app')

@section('content')
<div class="container">
    <h1>学習記録一覧</h1>
    <a href="{{ route('study_logs.create') }}" class="btn btn-primary mb-3">新規追加</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>教材名</th>
                <th>カテゴリ</th>
                <th>学習時間</th>
                <th>開始</th>
                <th>終了</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td>{{ $log->material_name }}</td>
                <td>{{ $log->category }}</td>
                <td>{{ gmdate("H:i:s", $log->duration) }}</td>
                <td>{{ $log->start_time }}</td>
                <td>{{ $log->end_time }}</td>
                <td>
                    <a href="{{ route('study_logs.edit', $log) }}" class="btn btn-sm btn-warning">編集</a>
                    <form action="{{ route('study_logs.destroy', $log) }}" method="POST" style="display:inline-block;"
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

    {{ $logs->links() }}
</div>
@endsection
