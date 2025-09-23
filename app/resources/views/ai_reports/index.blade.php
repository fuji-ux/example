@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">AIレポート一覧</h2>

    <!-- タブ切り替え -->
    <ul class="nav nav-tabs" id="reportTabs">
        <li class="nav-item">
            <a class="nav-link active" data-type="weekly" href="#">週次</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-type="monthly" href="#">月次</a>
        </li>
    </ul>

    <!-- 一覧を表示する部分 -->
    <div id="reportList" class="mt-3">
        {{-- 初期表示は週次レポート --}}
        @include('ai_reports.partials.list', ['reports' => $weeklyReports])
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const reportList = document.getElementById('reportList');

    document.querySelectorAll('#reportTabs .nav-link').forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            const type = this.getAttribute('data-type');

            // アクティブ切り替え
            document.querySelectorAll('#reportTabs .nav-link').forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            // 非同期リクエスト
            fetch("{{ url('/ai-reports/list') }}/" + type)
                .then(response => response.text())
                .then(html => {
                    reportList.innerHTML = html;
                });
        });
    });
});
</script>

@endsection