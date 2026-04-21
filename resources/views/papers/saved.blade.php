<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Saved Papers</title>

<link rel="stylesheet" href="{{ asset('css/papers.css') }}">
</head>

<div style="margin-bottom: 20px;">
        <a href="javascript:history.back()" class="back">&larr; Back</a>
        <span style="margin: 0 15px; color: #ccc;">|</span>
        <a href="{{ route('papers.index') }}" class="back" style="color: #666; font-weight: normal;">New Search</a>
    </div>

<div class="hero" style="text-align:left;">
    <h1>Saved Papers</h1>
    <p>Your bookmarked research papers</p>
</div>

@if(count($saved))

<div class="grid">

    @foreach($saved as $paper)
    <div class="result-card">

        <a class="result-title" href="{{ route('papers.show', $paper['id']) }}">
            {{ $paper['title'] }}
        </a>

        <div class="meta">
            <span class="badge">
                {{ $paper['year'] }}
            </span>
        </div>

        <div class="actions">

            <a href="{{ route('papers.show', $paper['id']) }}" class="btn-small primary">
                View Details
            </a>

            <form method="POST" action="{{ route('papers.unsave', $paper['id']) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-small">
                    Remove
                </button>
            </form>

        </div>

    </div>
    @endforeach

</div>

@else

<div class="empty">
    <h2>No saved papers</h2>
    <p>Start saving papers from search results.</p>
</div>

@endif
