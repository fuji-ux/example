@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">ユーザー管理</h1>

    {{-- ✅ 検索フォーム --}}
    <form method="GET" action="{{ route('users.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control" placeholder="名前・メールアドレスで検索"
                value="{{ request('keyword') }}">
            <button type="submit" class="btn btn-primary">検索</button>
        </div>
    </form>

    {{-- ✅ ユーザー一覧 --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>プレミアム</th>
                <th>操作</th>
                <th>アカウント削除</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->is_premium)
                    ✅ 有効
                    @else
                    ❌ 無効
                    @endif
                </td>
                <td>
                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_premium" value="{{ $user->is_premium ? 0 : 1 }}">
                        <button type="submit" class="btn btn-sm {{ $user->is_premium ? 'btn-danger' : 'btn-success' }}">
                            {{ $user->is_premium ? '解除' : '付与' }}
                        </button>
                    </form>
                </td>
                <td>
                    {{-- ✅ 削除ボタン --}}
                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('本当に削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">ユーザーが見つかりません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ✅ ページネーション --}}
    <div class="mt-3">
        {{ $users->links() }}
    </div>
</div>
@endsection