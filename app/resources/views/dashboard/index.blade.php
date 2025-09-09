@extends('layouts.app')

@section('content')
    <h1>ようこそ、{{ auth()->user()->name }} さん！</h1>
    <p>左のメニューから学習記録や予定を確認できます。</p>
@endsection
