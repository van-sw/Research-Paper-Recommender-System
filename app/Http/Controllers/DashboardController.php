<?php

namespace App\Http\Controllers;

use App\Services\PaperSearchService;

class DashboardController extends Controller
{
    public function index(PaperSearchService $service)
    {

        $total = $service->countPapers();
        $randomPapers = $service->getRandomPapers();

        // $latest = $service->getLatestPapers();

        // $featured = $service->getFeaturedPaperWithRecommendations();

        $topics = $service->getTrendingTopics();
        $papersPerYear = $service->getPapersPerYear();

        return view('dashboard', [
            'total' => $total,
            'randomPapers'   => $randomPapers,
            'topics'         => $topics,
            'papersPerYear' => $papersPerYear
        ]);
    }



    
}