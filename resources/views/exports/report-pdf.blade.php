<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Deteksi Edelweis</title>
    <style>
        @page {
            margin: 35px 30px 40px 30px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9.5px;
            color: #1e293b;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }

        /* ===== TOP HEADER (running) — pakai dompdf hack via page_text script di footer ===== */
        /* dompdf tidak support position:fixed dengan baik, jadi kita pakai inline di awal body */

        .top-bar {
            border-bottom: 2px solid #059669;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        .top-bar table {
            width: 100%;
            border-collapse: collapse;
        }

        .top-bar .brand {
            font-size: 12px;
            font-weight: bold;
            color: #059669;
        }

        .top-bar .meta {
            text-align: right;
            font-size: 8px;
            color: #64748b;
        }

        /* ===== TITLE ===== */
        h1.title {
            font-size: 14px;
            text-align: center;
            color: #0f172a;
            margin: 4px 0 2px 0;
            font-weight: bold;
        }

        .subtitle {
            text-align: center;
            font-size: 9px;
            color: #64748b;
            margin: 0 0 10px 0;
        }

        /* ===== FILTER INFO ===== */
        .filter-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 5px 8px;
            margin-bottom: 10px;
            font-size: 8.5px;
            color: #475569;
        }

        /* ===== SECTION TITLE ===== */
        .section {
            font-size: 9.5px;
            font-weight: bold;
            color: #0f172a;
            margin: 8px 0 4px 0;
            padding-bottom: 2px;
            border-bottom: 1px solid #cbd5e1;
        }

        /* ===== SUMMARY GRID — pakai HTML table biar dompdf-friendly ===== */
        table.summary-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 4px;
            margin-bottom: 8px;
        }

        table.summary-grid td {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 6px 4px;
            text-align: center;
            width: 20%;
            vertical-align: middle;
        }

        .summary-label {
            font-size: 7.5px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: block;
            margin-bottom: 2px;
        }

        .summary-value {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
        }

        .summary-value-sm {
            font-size: 10px;
            font-weight: bold;
            color: #0f172a;
        }

        /* ===== DISTRIBUTION ===== */
        table.distribution {
            width: 100%;
            border-collapse: separate;
            border-spacing: 4px;
            margin-bottom: 10px;
        }

        table.distribution td {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 5px;
            text-align: center;
            width: 33.33%;
            vertical-align: middle;
        }

        .dist-tag {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .tag-mekar { background: #fce7f3; color: #be185d; }
        .tag-sangat { background: #fbcfe8; color: #9d174d; }
        .tag-penyem { background: #d1fae5; color: #065f46; }

        .dist-value {
            font-size: 10px;
            font-weight: bold;
            color: #0f172a;
        }

        .dist-percent {
            font-size: 8px;
            color: #64748b;
        }

        /* ===== DATA TABLE ===== */
        table.data {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        table.data thead {
            display: table-header-group;
        }

        table.data th {
            background: #059669;
            color: #fff;
            padding: 5px 4px;
            text-align: left;
            font-weight: bold;
            font-size: 8px;
            border: 1px solid #047857;
        }

        table.data th.tc {
            text-align: center;
        }

        table.data td {
            padding: 4px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        table.data tr:nth-child(even) td {
            background: #f8fafc;
        }

        .tc { text-align: center; }
        .nowrap { white-space: nowrap; }

        .badge-guest {
            background: #fef3c7;
            color: #92400e;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
        }

        .empty-state {
            padding: 25px;
            text-align: center;
            color: #94a3b8;
            font-style: italic;
            font-size: 9px;
        }
    </style>
</head>
<body>

    {{-- HEADER (one-time at start, not running on each page) --}}
    <div class="top-bar">
        <table>
            <tr>
                <td class="brand">{{ $appName }}</td>
                <td class="meta">
                    Dicetak: {{ $generatedAt->format('d M Y H:i') }}<br>
                    Oleh: {{ $generatedBy }}
                </td>
            </tr>
        </table>
    </div>

    {{-- TITLE --}}
    <h1 class="title">LAPORAN DETEKSI KESEHATAN BUNGA EDELWEIS</h1>
    <p class="subtitle">
        Periode: {{ $from->format('d M Y') }} &mdash; {{ $to->format('d M Y') }}
        ({{ $summary['days'] }} hari)
    </p>

    {{-- FILTER INFO --}}
    @php
        $filterParts = [];
        if ($filters['condition']) {
            $filterParts[] = 'Kondisi: ' . str_replace('_', ' ', $filters['condition']);
        }
        if ($filters['source_method'] !== 'all') {
            $filterParts[] = 'Metode: ' . ($filters['source_method'] === 'camera' ? 'Kamera' : 'Upload');
        }
        if ($filters['user_source'] !== 'all') {
            $filterParts[] = 'Sumber: ' . ($filters['user_source'] === 'guest' ? 'Pengunjung' : 'Admin/User');
        }
    @endphp

    @if (!empty($filterParts))
        <div class="filter-info">
            <strong>Filter aktif:</strong> {{ implode(' &middot; ', $filterParts) }}
        </div>
    @endif

    {{-- SUMMARY --}}
    <div class="section">RINGKASAN</div>
    <table class="summary-grid">
        <tr>
            <td>
                <span class="summary-label">Total Deteksi</span>
                <span class="summary-value">{{ $summary['total'] }}</span>
            </td>
            <td>
                <span class="summary-label">Total Objek</span>
                <span class="summary-value">{{ $summary['total_objects'] }}</span>
            </td>
            <td>
                <span class="summary-label">Rata-rata / Hari</span>
                <span class="summary-value">{{ $summary['avg_per_day'] }}</span>
            </td>
            <td>
                <span class="summary-label">Avg Confidence</span>
                <span class="summary-value">{{ $summary['avg_confidence'] }}%</span>
            </td>
            <td>
                <span class="summary-label">Kondisi Dominan</span>
                <span class="summary-value-sm">
                    {{ $summary['dominant'] ? str_replace('_', ' ', $summary['dominant']) : '—' }}
                </span>
            </td>
        </tr>
    </table>

    {{-- DISTRIBUTION --}}
    @if ($summary['total'] > 0)
        @php $totalByClass = array_sum($summary['by_class']); @endphp
        <div class="section">DISTRIBUSI KONDISI</div>
        <table class="distribution">
            <tr>
                <td>
                    <span class="dist-tag tag-mekar">MEKAR</span><br>
                    <span class="dist-value">{{ $summary['by_class']['Mekar'] }} objek</span>
                    @if ($totalByClass > 0)
                        <span class="dist-percent">({{ round(($summary['by_class']['Mekar'] / $totalByClass) * 100, 1) }}%)</span>
                    @endif
                </td>
                <td>
                    <span class="dist-tag tag-sangat">SANGAT MEKAR</span><br>
                    <span class="dist-value">{{ $summary['by_class']['Sangat_Mekar'] }} objek</span>
                    @if ($totalByClass > 0)
                        <span class="dist-percent">({{ round(($summary['by_class']['Sangat_Mekar'] / $totalByClass) * 100, 1) }}%)</span>
                    @endif
                </td>
                <td>
                    <span class="dist-tag tag-penyem">PENYEMAIAN</span><br>
                    <span class="dist-value">{{ $summary['by_class']['Penyemaian'] }} objek</span>
                    @if ($totalByClass > 0)
                        <span class="dist-percent">({{ round(($summary['by_class']['Penyemaian'] / $totalByClass) * 100, 1) }}%)</span>
                    @endif
                </td>
            </tr>
        </table>
    @endif

    {{-- DETAIL TABLE --}}
    <div class="section">DETAIL DETEKSI ({{ $detections->count() }} data)</div>

    @if ($detections->isEmpty())
        <div class="empty-state">
            Tidak ada deteksi pada periode dan filter yang dipilih.
        </div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th class="tc" style="width: 30px;">ID</th>
                    <th style="width: 65px;">Tanggal</th>
                    <th class="tc" style="width: 30px;">Jam</th>
                    <th>Sumber</th>
                    <th class="tc" style="width: 45px;">Metode</th>
                    <th>Kondisi Dominan</th>
                    <th class="tc" style="width: 35px;">Objek</th>
                    <th class="tc" style="width: 50px;">Confidence</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detections as $d)
                    <tr>
                        <td class="tc"><strong>#{{ $d->id }}</strong></td>
                        <td class="nowrap">{{ $d->created_at->format('d M Y') }}</td>
                        <td class="tc">{{ $d->created_at->format('H:i') }}</td>
                        <td>
                            @if ($d->is_guest)
                                <span class="badge-guest">Guest</span>
                            @else
                                {{ $d->user->name ?? '—' }}
                            @endif
                        </td>
                        <td class="tc">{{ $d->source === 'camera' ? 'Kamera' : 'Upload' }}</td>
                        <td>{{ $d->dominant_label ? str_replace('_', ' ', $d->dominant_label) : '—' }}</td>
                        <td class="tc">{{ $d->object_count }}</td>
                        <td class="tc">{{ number_format($d->avg_confidence * 100, 1) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- 
        FOOTER & PAGE NUMBER pakai dompdf inline script.
        Ini cara WORKING untuk dompdf — dipanggil dari controller via setOption + custom CSS.
        Karena di controller kita pakai approach JavaScript-based numbering.
    --}}
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Laporan Deteksi Kesehatan Bunga Edelweis — Periode {{ $from->format('d M Y') }} s/d {{ $to->format('d M Y') }}";
            $size = 7;
            $font = $fontMetrics->getFont("DejaVu Sans", "normal");
            $width = $fontMetrics->getTextWidth($text, $font, $size);
            $pageW = $pdf->get_width();
            $pageH = $pdf->get_height();
            
            // Footer text di kiri
            $pdf->page_text(30, $pageH - 22, $text, $font, $size, [0.58, 0.64, 0.72]);
            
            // Page number di kanan
            $pdf->page_text($pageW - 70, $pageH - 22, "Hal {PAGE_NUM} / {PAGE_COUNT}", $font, $size, [0.58, 0.64, 0.72]);
        }
    </script>

</body>
</html>
