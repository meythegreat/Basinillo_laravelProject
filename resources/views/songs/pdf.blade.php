<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Music Library Export</title>
    <style>
        /* DOMPDF COMPATIBILITY NOTE:
           PDFs don't support Flexbox or Grid. We use Tables for layout.
           We use 'DejaVu Sans' for full UTF-8 support.
        */
        @page { margin: 25px; }
        
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            font-size: 11px; 
            color: #374151; /* Gray-700 */
        }

        /* --- HEADER DESIGN --- */
        .header-container {
            width: 100%;
            /* Fallback color */
            background-color: #6366f1; 
            /* Dashboard Gradient: Indigo -> Purple -> Pink */
            background-image: linear-gradient(to right, #6366f1, #a855f7, #ec4899);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .header-table { width: 100%; }
        .header-title { 
            font-size: 22px; 
            font-weight: bold; 
            margin: 0; 
            text-transform: uppercase; 
            letter-spacing: 1px;
        }
        .header-meta { 
            text-align: right; 
            font-size: 10px; 
            color: #e0e7ff; /* Indigo-100 */
            line-height: 1.4;
        }

        /* --- STATS CARDS (Simulated with Table) --- */
        .stats-container {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0; /* Gap between cards */
            margin-bottom: 25px;
            margin-left: -10px; /* Offset the spacing */
        }
        .stat-card {
            width: 33%;
            background-color: #f9fafb; /* Zinc-50 */
            border: 1px solid #e5e7eb; /* Zinc-200 */
            border-radius: 8px;
            padding: 12px;
        }
        .stat-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280; /* Gray-500 */
            margin-bottom: 4px;
        }
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #111827; /* Gray-900 */
        }

        /* --- SONGS TABLE --- */
        .data-table { 
            width: 100%; 
            border-collapse: collapse; 
            border: 1px solid #e5e7eb;
        }
        .data-table th { 
            background-color: #f3f4f6; /* Gray-100 */
            color: #4b5563; /* Gray-600 */
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #e5e7eb;
        }
        .data-table td { 
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }
        /* Zebra Striping */
        .data-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        /* Album Art */
        .cover-img {
            width: 35px;
            height: 35px;
            border-radius: 4px;
            object-fit: cover;
            border: 1px solid #ddd;
        }
        .placeholder-img {
            width: 35px;
            height: 35px;
            background-color: #e5e7eb;
            border-radius: 4px;
            text-align: center;
            line-height: 35px;
            font-size: 18px;
            color: #9ca3af;
        }
        /* Genre Badge simulation */
        .badge {
            background-color: #f3e8ff; /* Purple-100 */
            color: #7e22ce; /* Purple-700 */
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 9px;
            border: 1px solid #d8b4fe;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header-container">
        <table class="header-table">
            <tr>
                <td>
                    <h1 class="header-title">Music Dashboard</h1>
                    <div style="font-size: 10px; opacity: 0.9;">Professional Library Export</div>
                </td>
                <td class="header-meta">
                    <strong>Generated:</strong> {{ now()->format('M d, Y • h:i A') }}<br>
                    <strong>User:</strong> {{ auth()->user()->name ?? 'Guest' }}
                </td>
            </tr>
        </table>
    </div>

    <table class="stats-container">
        <tr>
            <td class="stat-card">
                <div class="stat-label">Total Tracks</div>
                <div class="stat-value">{{ $songs->count() }}</div>
            </td>
            <td class="stat-card">
                <div class="stat-label">Total Duration</div>
                <div class="stat-value">
                    @php
                        $totalSec = $songs->sum('duration_seconds');
                        $h = intdiv($totalSec, 3600);
                        $m = intdiv($totalSec % 3600, 60);
                    @endphp
                    {{ $h }}h {{ $m }}m
                </div>
            </td>
            <td class="stat-card">
                <div class="stat-label">Total Genres</div>
                <div class="stat-value">{{ $songs->unique('genre_id')->count() }}</div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">Cover</th>
                <th style="width: 32%;">Title</th>
                <th style="width: 25%;">Artist</th>
                <th style="width: 15%;">Genre</th>
                <th style="width: 10%;">Duration</th>
                <th style="width: 10%;">Year</th>
            </tr>
        </thead>
        <tbody>
            @foreach($songs as $song)
                <tr>
                    <td>
                        {{-- 
                            CRITICAL FOR PDF: 
                            Use public_path() for images. /storage/ won't work in DomPDF.
                        --}}
                        @if($song->photo && file_exists(public_path('storage/' . $song->photo)))
                            <img src="{{ public_path('storage/' . $song->photo) }}" class="cover-img">
                        @else
                            <div class="placeholder-img">♪</div>
                        @endif
                    </td>
                    <td style="font-weight: bold; color: #111827;">
                        {{ $song->title }}
                    </td>
                    <td>
                        {{ $song->artist ?? 'Unknown' }}
                    </td>
                    <td>
                        @if($song->genre)
                            <span class="badge">{{ $song->genre->name }}</span>
                        @else
                            <span style="color: #9ca3af;">—</span>
                        @endif
                    </td>
                    <td style="font-family: monospace;">
                        @if($song->duration_seconds)
                            {{ intdiv($song->duration_seconds, 60) }}:{{ str_pad($song->duration_seconds % 60, 2, '0', STR_PAD_LEFT) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $song->release_year ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Page generated by Music Dashboard System • {{ $songs->count() }} records found
    </div>

</body>
</html>