<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\StudyLog;
use App\AiReport;
use Carbon\Carbon;
use GuzzleHttp\Client;


class GenerateAiReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'command:name';
    protected $signature = 'ai:generate-reports {--type=weekly}';
    /**
     * The console command description.
     *
     * @var string
     */
    //protected $description = 'Command description';
    protected $description = 'Generate AI reports for premium users';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reportType = $this->option('type'); // weekly or monthly

        // === 期間設定 ===
        if ($reportType === 'weekly') {
            $periodStart = Carbon::now()->startOfWeek();
            $periodEnd   = Carbon::now()->endOfWeek();
        } else {
            $periodStart = Carbon::now()->startOfMonth();
            $periodEnd   = Carbon::now()->endOfMonth();
        }

        // === プレミアユーザー対象 ===
        $users = User::where('is_premium', true)->get();

        foreach ($users as $user) {
            $logs = StudyLog::where('user_id', $user->id)
                ->whereBetween('start_time', [$periodStart, $periodEnd])
                ->get();

            if ($logs->isEmpty()) {
                $this->info("No logs for user {$user->id}");
                continue;
            }

            // === 集計 ===
            $totalDuration = round($logs->sum('duration') / 60, 1);
            $avgFocus      = round($logs->avg('focus_score'), 2);
            $avgUnderstanding = round($logs->avg('understanding_score'), 2);

            // 曜日ごとの合計時間
            $byWeekday = $logs->groupBy(function ($log) {
                return Carbon::parse($log->start_time)->format('D'); // Mon, Tue, ...
            })->map(function ($group) {
                return round($group->sum('duration') / 60, 1);
            });

            // 時間帯ごとの合計時間
            $byTimeSlot = $logs->groupBy(function ($log) {
                $hour = Carbon::parse($log->start_time)->hour;
                if ($hour >= 5 && $hour < 12) return 'morning';
                if ($hour >= 12 && $hour < 17) return 'afternoon';
                if ($hour >= 17 && $hour < 21) return 'evening';
                return 'night';
            })->map(function ($group) {
                    return round($group->sum('duration') / 60, 1);
            });

            // JSON summary
            $summary = [
                'total_duration' => $totalDuration,
                'avg_focus' => $avgFocus,
                'avg_understanding' => $avgUnderstanding,
                'by_weekday' => $byWeekday,
                'by_timeslot' => $byTimeSlot,
            ];

            // === プロンプト作成 ===
            $prompt = "以下の学習データを分析し、日本語でやさしくわかりやすい{$reportType}レポートを作成してください。
                       文章は『です・ます調』で出力してください。
                       出力は必ずMarkdown形式で、以下の見出しを含めてください。

                        ## 学習状況の要約
                        - （100文字程度で簡潔に）

                        ## 学習の強み
                        - 箇条書きで3点

                        ## 改善点
                        - 箇条書きで3点

                        ## 次回へのアドバイス
                        - 箇条書きで3点

                        【学習データ】
                        合計学習時間: {$totalDuration}分
                        平均集中度: {$avgFocus}
                        平均理解度: {$avgUnderstanding}
                        ";

            // === OpenAI API 呼び出し（Guzzle） ===
            $client = new Client([
                'base_uri' => 'https://api.openai.com/v1/',
                'timeout'  => 30,
            ]);

            try {
                $response = $client->post('chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                        'Content-Type'  => 'application/json',
                    ],
                    'json' => [
                        'model' => 'gpt-4o-mini', // または 'gpt-3.5-turbo'
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are an AI study coach.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'max_tokens' => 500,
                    ],
                ]);

                $result = json_decode($response->getBody(), true);

                $aiText = $result['choices'][0]['message']['content'] ?? 'AI生成失敗';

                // --- ダミーテキスト ---
                //$aiText = "これはテスト用の {$reportType} レポートです。学習時間 {$totalDuration}分。";


            } catch (\Exception $e) {
                $this->error("AI生成失敗: " . $e->getMessage());
                $aiText = "AI生成に失敗しました（エラー時のダミーテキスト）";
            }

            // === unique_key 作成 ===
            $uniqueKey = $user->id . '_' . $reportType . '_' . $periodEnd->format('Ymd');

            // === DB保存 ===
            AiReport::firstOrCreate(
                ['unique_key' => $uniqueKey],
                [
                    'user_id'      => $user->id,
                    'report_type'  => $reportType,
                    'period_start' => $periodStart,
                    'period_end'   => $periodEnd,
                    'summary'      => json_encode($summary, JSON_UNESCAPED_UNICODE),
                    'ai_feedback'  => $aiText,
                ]
            );

            $this->info("Report created for user {$user->id} ({$reportType})");
        }
    }
}
