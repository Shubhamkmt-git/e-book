<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $book['title'] }} — Full Edition</title>
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
            max-width: 820px;
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
            color: #94a3b8;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .chapter-box {
            margin: 35px 0;
            padding: 30px;
            background: #faf5ff;
            border: 1px solid #e9d5ff;
            border-radius: 12px;
        }
        .chapter-box h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 28px;
            color: #581c87;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }
        p { margin-bottom: 18px; font-size: 16px; }
        .highlight-box {
            background: #f1f5f9;
            border-left: 4px solid #7a58a9;
            padding: 20px;
            margin: 25px 0;
            font-style: italic;
        }
        .footer {
            margin-top: 50px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            color: #94a3b8;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .container { box-shadow: none; border: none; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="badge">Official Full Edition (DRM-Free)</span>
            <h1>{{ $book['title'] }}</h1>
            <div class="subtitle">By {{ $book['author'] }} • {{ $book['category'] }}</div>
            <div class="meta">
                <span>Verified Digital Publication</span>
                <span>•</span>
                <span>{{ $book['pages'] }} Pages</span>
                <span>•</span>
                <span>{{ $book['format'] }}</span>
            </div>
        </div>

        <div class="content">
            <h2 style="font-family: 'Bebas Neue', sans-serif; font-size: 26px; color: #0f172a; margin-bottom: 15px; text-transform: uppercase;">About This Publication</h2>
            <p>{{ $book['description'] }}</p>

            <div class="highlight-box">
                {{ $book['highlights'][0] ?? 'Timeless mental models, robust system design, and practical architecture patterns designed for lifelong compounding leverage.' }}
            </div>

            <div class="chapter-box">
                <h2>{{ $book['sample_content']['chapter_title'] ?? 'Chapter 1: Foundational Frameworks' }}</h2>
                <p>{{ $book['sample_content']['intro'] ?? 'Welcome to the complete publication. In this chapter, we unpack the fundamental principles and systematic mental models that govern high-leverage execution.' }}</p>

                @if(!empty($book['sample_content']['sections']))
                    @foreach($book['sample_content']['sections'] as $sec)
                        <h3 style="font-family: 'Poppins', sans-serif; font-size: 17px; font-weight: 700; color: #3b0764; margin: 20px 0 8px 0;">{{ $sec['heading'] }}</h3>
                        <p>{{ $sec['content'] }}</p>
                    @endforeach
                @endif
            </div>

            <h2 style="font-family: 'Bebas Neue', sans-serif; font-size: 26px; color: #0f172a; margin: 35px 0 15px 0; text-transform: uppercase;">Table of Contents & Curriculum</h2>
            <div style="font-family: 'Poppins', sans-serif; font-size: 14px; line-height: 2;">
                @if(!empty($book['chapters']))
                    @foreach($book['chapters'] as $chap)
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px dotted #cbd5e1; padding: 6px 0;">
                            <span><strong>{{ $chap['number'] }}.</strong> {{ $chap['title'] }}</span>
                            <span style="color: #64748b;">{{ $chap['pages'] }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'E-Book Store') }}. Purchased by {{ $customer?->name ?? 'Verified Reader' }} ({{ $customer?->email ?? '' }}).</p>
            <p>Transaction Reference: {{ $purchase?->transaction_id ?? 'N/A' }}</p>
        </div>
    </div>
</body>
</html>
