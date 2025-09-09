<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StudyLog;
use App\Material;

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
        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after_or_equal:start_time',
            'category'   => 'required|string|max:50',
        ]);

        $duration = strtotime($request->end_time) - strtotime($request->start_time);

        StudyLog::create([
            'user_id' => auth()->id(),
            'material_id' => $request->material_id,
            'material_name' => Material::find($request->material_id)->name,
            'category' => $request->category,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration' => $duration,
            'focus_score' => $request->focus_score,
            'understanding_score' => $request->understanding_score,
            'motivation_score' => $request->motivation_score,
            'comment' => $request->comment,
            'ai_analyzed' => false,
        ]);

        return redirect()->route('study_logs.index')->with('success', '学習記録を追加しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
