@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-bold mb-4">マイページ</h1>

    <form action="{{ route('mypage.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- 名前 --}}
        <div class="mb-4">
            <label class="block text-gray-700">名前</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- メールアドレス --}}
        <div class="mb-4">
            <label class="block text-gray-700">メールアドレス</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror">
            @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- パスワード --}}
        <div class="mb-4">
            <label class="block text-gray-700">新しいパスワード（変更する場合のみ）</label>
            <input type="password" name="password"
                class="w-full border rounded px-3 py-2 @error('password') border-red-500 @enderror">
            @error('password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- パスワード確認 --}}
        <div class="mb-4">
            <label class="block text-gray-700">パスワード確認</label>
            <input type="password" name="password_confirmation"
                class="w-full border rounded px-3 py-2">
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            更新
        </button>
    </form>
</div>
@endsection