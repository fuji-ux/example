<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'is_premium',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
    ];

    // リレーション
    public function studyLogs()
    {
        return $this->hasMany(StudyLog::class);
    }

    public function aiReports()
    {
        return $this->hasMany(AiReport::class);
    }

    public function badges()
    {
        return $this->hasMany(Badge::class);
    }

    public function studySchedules()
    {
        return $this->hasMany(StudySchedule::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
