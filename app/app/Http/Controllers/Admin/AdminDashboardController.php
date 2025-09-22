<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\StudyLog;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ユーザー数や統計を取得
        $userCount = User::count();
        $premiumCount = User::where('is_premium', true)->count();

        return view('admin.dashboard', compact('userCount', 'premiumCount'));
    }
}