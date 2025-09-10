<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'material_id', 'material_name', 'category',
        'start_time', 'end_time', 'duration',
        'focus_score', 'understanding_score', 'motivation_score',
        'comment', 'ai_analyzed',
    ];

    protected $casts = [
        'ai_analyzed' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}