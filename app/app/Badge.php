<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = [
        'user_id', 'badge_type', 'condition', 'awarded_at',
    ];

    // リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
