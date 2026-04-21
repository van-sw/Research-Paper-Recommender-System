<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trending Papers Dashboard</title>

<link rel="stylesheet" href="{{ asset('css/papers.css') }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>

<div class="navbar">
    <div class="nav-container">

        <div class="nav-links">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('papers.index') }}">Search Papers</a>
            <a href="{{ route('papers.saved') }}">Saved</a>
        </div>

    </div>
</div>

<div class="section">
    <h2>Dashboard Overview</h2>

    <div class="grid">
        <div class="card">
            <div>Total Papers (PG Dataset)</div>
            <div class="metric">{{ number_format($total) }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div style="margin-bottom:10px; font-weight:600;">
        Trending Topics
    </div>

    <div class="topics">
        @foreach($topics as $topic)
            <span class="topic-badge">
                {{ $topic['name'] }}
            </span>
        @endforeach
    </div>
</div>

<div class="section">
    <h2>Papers by Year</h2>

    <div class="card">
        <div style="margin-bottom:10px; font-weight:600;">
            Publication Trend (2020–2025)
        </div>
        <canvas id="papersChart"></canvas>
    </div>
</div>


<div class="section">
    <h2>Discover Papers</h2>

    <div class="grid">
        @foreach($randomPapers as $paper)
        <div class="paper">
            <a href="{{ route('papers.show', $paper->id) }}">
                {{ $paper->title }}
            </a>
            <div class="small">{{ $paper->year }}</div>
        </div>
        @endforeach
    </div>
</div>

</body>
</html>

<script>
const labels = @json(array_keys($papersPerYear->toArray()));
const data = @json(array_values($papersPerYear->toArray()));

const ctx = document.getElementById('papersChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Papers Published',
            data: data,
            borderRadius: 8,
            barThickness: 30,
            backgroundColor: 'rgba(37, 99, 235, 0.8)',   // primary
            hoverBackgroundColor: 'rgba(29, 78, 216, 1)', // darker
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: 10
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: '#111827',
                titleColor: '#fff',
                bodyColor: '#fff',
                padding: 10,
                cornerRadius: 6,
                displayColors: false,
                callbacks: {
                    label: function(context) {
                        return context.raw + ' papers';
                    }
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: '#6b7280'
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: '#e5e7eb'
                },
                ticks: {
                    color: '#6b7280'
                }
            }
        }
    }
});
</script>