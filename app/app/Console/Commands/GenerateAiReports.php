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
            $totalDuration = $logs->sum('duration');
            $avgFocus      = round($logs->avg('focus_score'), 2);
            $avgUnderstanding = round($logs->avg('understanding_score'), 2);

            // === プロンプト作成 ===
            $prompt = "以下の学習データをもとに{$reportType}レポートをMarkdown形式で作成してください。

                    【学習データ】
                    合計学習時間: {$totalDuration}分
                    平均集中度: {$avgFocus}
                    均理解度: {$avgUnderstanding}

                    【出力フォーマット】
                    1. 学習状況の要約（100文字程度）
                    2. 学習の強み（3点、箇条書き）
                    3. 改善が必要な点（3点、箇条書き）
                    4. 次回に向けた具体的アドバイス（箇条書き）
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
                    'summary'      => $aiText,
                    'ai_feedback'  => $aiText,
                ]
            );

            $this->info("Report created for user {$user->id} ({$reportType})");
        }
    }
}
