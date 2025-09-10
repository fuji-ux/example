<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StudyLog;
use App\Material;
use Carbon\Carbon;

class StudyLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logs = StudyLog::where('user_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        return view('study_logs.index', compact('logs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $materials = Material::where('user_id', auth()->id())->get();
        return view('study_logs.create', compact('materials'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. バリデーションルールを厳密な形式に修正し、全項目を検証
        $validated = $request->validate([
            'material_id'         => 'required|exists:materials,id',
            'start_time'          => 'required|date_format:Y-m-d\TH:i',
            'end_time'            => 'required|date_format:Y-m-d\TH:i|after_or_equal:start_time',
            'focus_score'         => 'required|integer|min:1|max:5',
            'understanding_score' => 'required|integer|min:1|max:5',
            'motivation_score'    => 'required|integer|min:1|max:5',
            'comment'             => 'nullable|string',
        ]);
        
        // 2. Materialモデルのデータを一度だけ取得
        $material = Material::findOrFail($validated['material_id']);

        // 3. durationをCarbonで正確に計算
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = Carbon::parse($validated['end_time']);
        $duration = $endTime->diffInSeconds($startTime);

        // 4. バリデーション済みのデータと追加情報を組み合わせてレコードを作成
        StudyLog::create([
            'user_id'             => auth()->id(),
            'material_id'         => $validated['material_id'],
            'material_name'       => $material->name,
            'category'            => $material->category, // materialテーブルから取得
            'start_time'          => $startTime,
            'end_time'            => $endTime,
            'duration'            => $duration,
            'focus_score'         => $validated['focus_score'],
            'understanding_score' => $validated['understanding_score'],
            'motivation_score'    => $validated['motivation_score'],
            'comment'             => $validated['comment'],
            'ai_analyzed'         => false,
        ]);

        return redirect()->route('study_logs.index')->with('success', '学習記録を追加しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(StudyLog $study_log)
    {
        if ($study_log->user_id !== auth()->id()) {
            abort(403, '権限がありません');
        }

        return view('study_logs.show', compact('study_log'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(StudyLog $study_log)
    {
        if ($study_log->user_id !== auth()->id()) {
            abort(403, '権限がありません');
        }

        $materials = Material::where('user_id', auth()->id())->get();

        return view('study_logs.edit', compact('study_log', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudyLog $study_log)
    {
        if ($study_log->user_id !== auth()->id()) {
            abort(403, '権限がありません');
        }

        // 1. バリデーションルールをビューの形式に合わせる
        $validated = $request->validate([
            'material_id'         => 'required|exists:materials,id',
            'start_time'          => 'required|date_format:Y-m-d\TH:i',
            'end_time'            => 'required|date_format:Y-m-d\TH:i|after_or_equal:start_time',
            'focus_score'         => 'required|integer|min:1|max:5',
            'understanding_score' => 'required|integer|min:1|max:5',
            'motivation_score'    => 'required|integer|min:1|max:5',
            'comment'             => 'nullable|string',
        ]);
        
        // 2. Materialモデルのデータを一度だけ取得
        $material = Material::findOrFail($validated['material_id']);

        // 3. durationをCarbonで正確に計算
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = Carbon::parse($validated['end_time']);
        $duration = $endTime->diffInSeconds($startTime);

        // 4. バリデーション済みのデータと追加情報を組み合わせてレコードを更新
        $study_log->update([
            'material_id'         => $validated['material_id'],
            'material_name'       => $material->name,
            'category'            => $material->category,
            'start_time'          => $startTime,
            'end_time'            => $endTime,
            'duration'            => $duration,
            'focus_score'         => $validated['focus_score'],
            'understanding_score' => $validated['understanding_score'],
            'motivation_score'    => $validated['motivation_score'],
            'comment'             => $validated['comment'],
        ]);

        return redirect()->route('study_logs.index')->with('success', '学習記録を更新しました');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudyLog $study_log)
    {
        if ($study_log->user_id !== auth()->id()) {
            abort(403, '権限がありません');
        }

        $study_log->delete();

        return redirect()->route('study_logs.index')->with('success', '学習記録を削除しました');
    }
}
