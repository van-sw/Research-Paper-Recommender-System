<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SavedPaperController extends Controller
{
    public function store(Request $request)
    {
        $saved = session()->get('saved_papers', []);

        $saved[$request->id] = [
            'id' => $request->id,
            'title' => $request->title,
            'year' => $request->year,
        ];

        session(['saved_papers' => $saved]);

        return back()->with('success', 'Paper saved!');
    }

    public function index()
    {
        $saved = session()->get('saved_papers', []);
        return view('papers.saved', compact('saved'));
    }

    public function destroy($id)
    {
        $saved = session()->get('saved_papers', []);
        unset($saved[$id]);

        session(['saved_papers' => $saved]);

        return back()->with('success', 'Removed!');
    }
}
