<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Services\PaperSearchService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PaperController extends Controller
{
    public function index(Request $request, PaperSearchService $searchService)
    {
        $results = [];

        if ($request->filled('q')) {

            $queryResults = $searchService->search($request->q);
            $allResults = iterator_to_array($queryResults);

            if ($request->filled('year')) {
                $year = (int) $request->year;

                $allResults = array_filter($allResults, function ($item) use ($year) {
                    return isset($item['year']) && $item['year'] >= $year;
                });
            }

            if ($request->filled('sort')) {

                if ($request->sort === 'latest') {
                    usort($allResults, fn($a, $b) => ($b['year'] ?? 0) <=> ($a['year'] ?? 0));
                }

                if ($request->sort === 'oldest') {
                    usort($allResults, fn($a, $b) => ($a['year'] ?? 0) <=> ($b['year'] ?? 0));
                }
            }

            $page = LengthAwarePaginator::resolveCurrentPage();
            $perPage = (int) $request->get('limit', 10);

            $items = array_slice($allResults, ($page - 1) * $perPage, $perPage);

            $results = new LengthAwarePaginator(
                $items,
                count($allResults),
                $perPage,
                $page,
                [
                    'path' => request()->url(),
                    'query' => request()->query()
                ]
            );
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