<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // 検索機能
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('name', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%");
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'is_premium' => 'required|boolean',
        ]);

        $user->is_premium = $request->is_premium;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'プレミアムステータスを更新しました。');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'ユーザーを削除しました。');
    }
}
