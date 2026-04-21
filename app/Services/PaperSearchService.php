<?php

namespace App\Services;

use Google\Cloud\BigQuery\BigQueryClient;
use App\Models\Paper;
use Illuminate\Support\Facades\Cache;

class PaperSearchService
{
    protected $bigQuery;
    protected $dataset;
    protected $projectId;

    public function __construct()
    {
        $this->bigQuery = new BigQueryClient([
            'projectId' => env('BIGQUERY_PROJECT_ID'),
        ]);

        $this->dataset   = env('BIGQUERY_DATASET');
        $this->projectId = env('BIGQUERY_PROJECT_ID');
    }

    public function search($query)
    {
        $sql = "
            SELECT id, title, abstract, year
            FROM `{$this->projectId}.{$this->dataset}.original_papers`
            WHERE CONTAINS_SUBSTR(title, @query)
               OR CONTAINS_SUBSTR(abstract, @query)
            LIMIT 100
        ";

        $jobConfig = $this->bigQuery
            ->query($sql)
            ->parameters([
                'query' => $query
            ]);

        return $this->bigQuery->runQuery($jobConfig);
    }

    public function findById($id)
    {
        $sql = "
            SELECT id, title, abstract, year, authors, n_citation
            FROM `{$this->projectId}.{$this->dataset}.original_papers`
            WHERE LOWER(TRIM(CAST(id AS STRING)))
                = LOWER(TRIM(CAST(@id AS STRING)))
            LIMIT 1
        ";

        $jobConfig = $this->bigQuery
            ->query($sql)
            ->parameters([
                'id' => trim($id)
            ]);

        $results = $this->bigQuery->runQuery($jobConfig);

        foreach ($results as $row) {
            return $row;
        }

        return null;
    }

    // public function getTrendingPapers()
    // {
    //     return Paper::whereNotNull('embedding')
    //         ->whereBetween('year', [2020, 2025])
    //         ->orderByDesc('n_citation')
    //         ->limit(5)
    //         ->get(['id', 'title', 'year', 'n_citation']);
    // }

    // public function getMostCitedPapers()
    // {
    //     return Paper::whereNotNull('embedding')
    //         ->whereBetween('year', [2020, 2025])
    //         ->orderByDesc('n_citation')
    //         ->limit(5)
    //         ->get(['id', 'title', 'year', 'n_citation']);
    // }

    public function getRandomPapers()
    {
        return Paper::dataset()
            ->inRandomOrder()
            ->limit(12)
            ->get();
    }

    public function getLatestPapers()
    {
        return Paper::dataset()
            ->orderByDesc('year')
            ->limit(5)
            ->get();
    }

    public function countPapers()
    {
        return Paper::dataset()->count();
    }

    public function getPapersPerYear()
    {
        return Cache::remember('papers_per_year', 3600, function () {
            return \App\Models\Paper::selectRaw('year, COUNT(*) as total')
                ->groupBy('year')
                ->orderBy('year')
                ->pluck('total', 'year');
        });
    }


    /*
    |--------------------------------------------------------------------------
    | TRENDING TOPICS
    |--------------------------------------------------------------------------
    | Approximation from title keywords
    |--------------------------------------------------------------------------
    */
    public function getTrendingTopics()
    {
        return [
            ['name' => 'Machine Learning', 'count' => 188],
            ['name' => 'NLP',              'count' => 142],
            ['name' => 'Computer Vision',  'count' => 117],
            ['name' => 'Bioinformatics',   'count' => 96],
            ['name' => 'LLM',              'count' => 88],
        ];
    }
}