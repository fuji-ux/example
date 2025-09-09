<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudyLog;
use App\Models\StudySchedule;
use App\Models\Badge;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user(); // ログイン中のユーザー取得
        return view('dashboard.index', compact('user'));
    }
}