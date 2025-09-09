<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudySchedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'start_time', 'end_time', 'memo',
    ];

    // リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}