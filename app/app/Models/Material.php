<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'category',
    ];

    // リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studyLogs()
    {
        return $this->hasMany(StudyLog::class);
    }
}