@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">管理者ダッシュボード</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">ユーザー数</h5>
                    <p class="display-4">{{ $userCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">プレミアム会員数</h5>
                    <p class="display-4">{{ $premiumCount }}</p>
                </div>
            </div>
        </div>
    
</div>
@endsection
