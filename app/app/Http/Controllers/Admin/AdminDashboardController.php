<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StudyLog;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $logCount = StudyLog::count();
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'userCount', 'logCount', 'recentUsers'
        ));
    }
}