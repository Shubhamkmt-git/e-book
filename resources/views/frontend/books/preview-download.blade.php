<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Preview: {{ $book['title'] }} — {{ $book['author'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Lora', Georgia, serif;
            color: #1e293b;
            background: #f8fafc;
            line-height: 1.8;
            padding: 40px 20px;
        }
        .container {
            max-width: 780px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 50px 45px;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.08);
        }
        .header {
            border-bottom: 2px solid #7a58a9;
            padding-bottom: 25px;
            margin-bottom: 35px;
        }
        .badge {
            display: inline-block;
            background: #f3e8ff;
            color: #7a58a9;
            font-family: 'Poppins', sans-serif;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 4px 12px;
            border-radius: 9999px;
            margin-bottom: 12px;
        }
        h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 42px;
            line-height: 1.1;
            color: #0f172a;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .subtitle {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #64748b;
            margin-bottom: 12px;
            font-weight: 500;
        }
        .meta {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            color: #475569;
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
        .meta strong { color: #0f172a; }
        .quote-box {
            background: #faf5ff;
            border-left: 4px solid #7a58a9;
            padding: 16px 20px;
            border-radius: 0 12px 12px 0;
            margin: 28px 0;
            font-style: italic;
            color: #4c1d95;
            font-size: 15px;
        }
        .section-title {
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin: 32px 0 12px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 6px;
        }
        p {
            font-size: 16px;
            color: #334155;
            margin-bottom: 18px;
        }
        .toc-list {
            list-style: none;
            margin: 20px 0;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
        }
        .toc-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 14px;
            border-radius: 8px;
            background: #f8fafc;
            margin-bottom: 6px;
            border: 1px solid #f1f5f9;
        }
        .toc-item.active {
            background: #f3e8ff;
            border-color: #d8b4fe;
            font-weight: 600;
            color: #6b21a8;
        }
        .cta-box {
            margin-top: 50px;
            padding: 30px;
            border-radius: 14px;
            background: linear-gradient(135deg, #4c1d95 0%, #7a58a9 100%);
            color: white;
            text-align: center;
            font-family: 'Poppins', sans-serif;
        }
        .cta-box h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 28px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .cta-box p {
            color: #e9d5ff;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .cta-box .price {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 32px;
            color: #fef08a;
            display: block;
            margin-bottom: 16px;
        }
        .btn-print {
            display: inline-block;
            background: white;
            color: #581c87;
            padding: 10px 24px;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            border: none;
        }
        @media print {
            body { background: white; padding: 0; }
            .container { box-shadow: none; border: none; padding: 20px; }
            .btn-print { display: none; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <span class="badge">Official Free Sample Preview ({{ $book['sample_pages'] ?? 25 }} Pages)</span>
        <h1>{{ $book['title'] }}</h1>
        @if(!empty($book['subtitle']))
            <div class="subtitle">{{ $book['subtitle'] }}</div>
        @endif
        <div class="meta">
            <div><strong>Author:</strong> {{ $book['author'] }} ({{ $book['author_role'] ?? 'Author' }})</div>
            <div><strong>Genre:</strong> {{ $book['category'] }}</div>
            <div><strong>Full Edition:</strong> {{ $book['pages'] ?? 350 }} Pages ({{ $book['format'] ?? 'PDF/EPUB' }})</div>
            <div><strong>ISBN:</strong> {{ $book['isbn'] ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- About this Sample Preview -->
    <div class="quote-box">
        “{{ $book['description'] }}”
    </div>

    <h2 class="section-title">Table of Contents (Full Book Overview)</h2>
    <ul class="toc-list">
        @if(!empty($book['chapters']))
            @foreach($book['chapters'] as $ch)
                <li class="toc-item {{ !empty($ch['is_sample']) ? 'active' : '' }}">
                    <span><strong>{{ is_array($ch) ? ($ch['number'] ?? '') . '. ' . ($ch['title'] ?? '') : $ch }}</strong></span>
                    <span>{{ is_array($ch) ? ($ch['pages'] ?? '') : '' }} {{ !empty($ch['is_sample']) ? '★ (Included in this Preview)' : '' }}</span>
                </li>
            @endforeach
        @endif
    </ul>

    <!-- Chapter Sample Preview -->
    @if(!empty($book['sample_content']))
        <h2 class="section-title" style="margin-top: 40px;">{{ $book['sample_content']['chapter_title'] ?? 'Chapter 1 Sample' }}</h2>
        <div style="font-family: 'Poppins', sans-serif; font-size: 12px; color: #7a58a9; font-weight: 600; margin-bottom: 16px;">
            Estimated reading time: {{ $book['sample_content']['reading_time'] ?? '12 mins' }}
        </div>
        
        <p style="font-size: 17px; font-weight: 600; color: #1e293b; line-height: 1.8;">
            {{ $book['sample_content']['intro'] ?? '' }}
        </p>

        @if(!empty($book['sample_content']['sections']))
            @foreach($book['sample_content']['sections'] as $sec)
                <h3 style="font-family: 'Poppins', sans-serif; font-size: 16px; font-weight: 700; color: #0f172a; margin: 24px 0 8px;">
                    {{ $sec['heading'] }}
                </h3>
                <p>
                    {{ $sec['content'] }}
                </p>
            @endforeach
        @endif
    @else
        <h2 class="section-title">Sample Introduction</h2>
        <p>{{ $book['description'] }}</p>
    @endif

    <!-- CTA Box -->
    <div class="cta-box">
        <h3>Enjoying This Sample Preview?</h3>
        <p>Unlock all {{ $book['pages'] ?? 350 }} pages, diagrams, bonus cheat-sheets, and multi-format EPUB + PDF bundle.</p>
        <span class="price">Only {{ $book['price'] }} <span style="font-size: 14px; text-decoration: line-through; opacity: 0.7;">{{ $book['original_price'] ?? '' }}</span></span>
        <button onclick="window.print()" class="btn-print">Save / Print Sample as PDF</button>
    </div>
</div>

</body>
</html>
