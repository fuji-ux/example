<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AiReport extends Model
{
    protected $fillable = [
        'user_id', 'report_type', 'period_start', 'period_end',
        'unique_key', 'summary', 'ai_feedback',
    ];

    protected $casts = [
        'summary' => 'array',
    ];

    // リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
