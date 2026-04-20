<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Services\PaperSearchService;
use Illuminate\Http\Request;

class PaperController extends Controller
{
    public function index(Request $request, PaperSearchService $searchService)
    {
        $results = [];
        
        if ($request->filled('q')) {
            $queryResults = $searchService->search($request->q);
            $results = iterator_to_array($queryResults);
        }
        
        return view('papers.index', compact('results'));
    }

    public function show($id, PaperSearchService $searchService)
    {
        $bqDetails = $searchService->findById($id);

        $paper = Paper::find($id);
        
        $recommendations = [];
        if ($paper) {
            $recommendations = $paper->getRecommendations();
        }
        
        return view('papers.show', compact('bqDetails', 'paper', 'recommendations', 'id'));
    }
}