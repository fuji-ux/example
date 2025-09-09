<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Material;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $materials = Material::where('user_id', auth()->id())
                             ->orderBy('created_at', 'desc')
                             ->paginate(10);

        return view('materials.index', compact('materials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('materials.create');
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
            'name'     => 'required|string|max:255',
            'category' => 'required|string|max:50',
        ]);

        Material::create([
            'user_id'  => auth()->id(),
            'name'     => $request->name,
            'category' => $request->category,
        ]);

        return redirect()->route('materials.index')->with('success', '教材を追加しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        if ($material->user_id !== auth()->id()) {
            abort(403, '権限がありません');
        }

        return view('materials.show', compact('material'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Material $material)
    {
        if ($material->user_id !== auth()->id()) {
            abort(403, '権限がありません');
        }

        return view('materials.edit', compact('material'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Material $material)
    {
        if ($material->user_id !== auth()->id()) {
            abort(403, '権限がありません');
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'required|string|max:50',
        ]);

        $material->update([
            'name'     => $request->name,
            'category' => $request->category,
        ]);

        return redirect()->route('materials.index')->with('success', '教材を更新しました');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        if ($material->user_id !== auth()->id()) {
            abort(403, '権限がありません');
        }

        $material->delete();

        return redirect()->route('materials.index')->with('success', '教材を削除しました');
    }
}
