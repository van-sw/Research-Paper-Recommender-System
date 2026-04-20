<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $bqDetails['title'] ?? 'Paper Details' }}</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background: #f4f7f6; color: #333; max-width: 900px; margin: 0 auto; padding: 40px 20px; line-height: 1.6; }
        a.back { display: inline-block; margin-bottom: 20px; color: #2563eb; text-decoration: none; font-weight: bold; }
        a.back:hover { text-decoration: underline; }
        .main-paper { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 40px; }
        .main-paper h1 { margin-top: 0; color: #111; }
        .main-paper p { text-align: justify; }
        h2 { border-bottom: 2px solid #ddd; padding-bottom: 10px; color: #444; }
        .rec-card { background: white; padding: 15px 20px; border-radius: 8px; margin-bottom: 15px; border-left: 5px solid #10b981; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .rec-card a { color: #047857; text-decoration: none; font-size: 1.1em; font-weight: bold; }
        .rec-card a:hover { text-decoration: underline; }
        .meta { color: #666; font-size: 0.9em; }
    </style>
</head>
<body>
    <div style="margin-bottom: 20px;">
        <a href="javascript:history.back()" class="back">&larr; Back</a>
        <span style="margin: 0 15px; color: #ccc;">|</span>
        <a href="{{ route('papers.index') }}" class="back" style="color: #666; font-weight: normal;">New Search</a>
    </div>

    <div class="main-paper">
        @if($bqDetails)
            <h1>{{ $bqDetails['title'] }}</h1>
            
            <div class="meta">
                <strong>Published:</strong> {{ $bqDetails['year'] }} &nbsp;|&nbsp;
                <strong>Citations:</strong> {{ $bqDetails['n_citation'] ?? 0 }}
            </div>
            
            @if(!empty($bqDetails['authors']))
                <div class="authors" style="margin-top: 10px; font-size: 1.05em; color: #1e40af;">
                    <strong>Authors:</strong> 
                    @php
                        $authorList = data_get($bqDetails, 'authors.list', []);
                        if (is_array($authorList)) {
                            echo collect($authorList)->map(function($item) {
                                return data_get($item, 'element.name') ?? data_get($item, 'name');
                            })->filter()->implode(', ');
                        } elseif (is_string($bqDetails['authors'])) {
                            echo $bqDetails['authors'];
                        }
                    @endphp
                </div>
            @endif
            
            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
            
            <h3>Abstract</h3>
            @if(!empty($bqDetails['abstract']))
                <p>{{ $bqDetails['abstract'] }}</p>
            @else
                <p><em>No abstract available for this paper.</em></p>
            @endif
        @else
            <div style="text-align: center; padding: 20px;">
                <h1 style="color: #999;">📄 Paper Metadata Unavailable</h1>
                <p>This paper could not be found in the BigQuery dataset.</p>
            </div>
        @endif
    </div>

    @if($paper)
        <h2>AI Recommended Papers</h2>
        <p>Based on content similarity:</p>

        <div class="recommendations">
            @forelse($recommendations as $rec)
                <div class="rec-card">
                    <a href="{{ route('papers.show', $rec['id']) }}">{{ $rec['title'] }}</a>
                    <div class="meta"><strong>Published:</strong> {{ $rec['year'] }}</div>
                </div>
            @empty
                <p>No similar papers found in the database.</p>
            @endforelse
        </div>
    @else
        <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; border-left: 5px solid #ffeeba;">
            <strong>Note:</strong> Vector embeddings have not been generated for this specific paper yet. AI recommendations are currently limited to the 2020-2025 dataset.
        </div>
    @endif
</body>
</html>