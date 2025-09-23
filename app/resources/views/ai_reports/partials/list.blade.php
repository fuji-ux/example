    @if($reports->isEmpty())
    <div class="alert alert-info">
        まだAIレポートはありません。
    </div>
    @else
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>期間</th>
                <th>種類</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                <td>{{ $report->period_start }} ~ {{ $report->period_end }}</td>
                <td>
                    @if($report->report_type === 'weekly')
                    週次
                    @elseif($report->report_type === 'monthly')
                    月次
                    @else
                    {{ $report->report_type }}
                    @endif
                </td>
                <td>
                    <a href="{{ route('ai-reports.show', $report) }}" class="btn btn-sm btn-outline-primary">
                        詳細を見る
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
