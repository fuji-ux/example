<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\AiReport;
use App\User;
use Carbon\Carbon;

class AiReportSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('is_premium', 1)->first();

        if (!$user) {
            $user = User::factory()->create([
                'is_premium' => 1,
                'email' => 'premium@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        AiReport::create([
            'user_id' => $user->id,
            'report_type' => 'weekly',
            'period_start' => Carbon::now()->subWeek()->startOfWeek(),
            'period_end'   => Carbon::now()->subWeek()->endOfWeek(),
            'unique_key'   => uniqid(),
            'summary'      => '先週は合計500分の学習を行いました。',
            'ai_feedback'  => '集中度が高く、良いペースです。',
        ]);

        AiReport::create([
            'user_id' => $user->id,
            'report_type' => 'monthly',
            'period_start' => Carbon::now()->subMonth()->startOfMonth(),
            'period_end'   => Carbon::now()->subMonth()->endOfMonth(),
            'unique_key'   => uniqid(),
            'summary'      => '先月は合計2000分の学習を行いました。',
            'ai_feedback'  => '学習時間が安定しています。この調子を維持しましょう。',
        ]);
    }
}
