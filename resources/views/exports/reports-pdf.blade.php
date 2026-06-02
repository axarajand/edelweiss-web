<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Deteksi Edelweiss</title>
    <style>
        @page {
            margin: 1.5cm 1.5cm;
        }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.4;
        }
        h1 {
            font-size: 16pt;
            color: #047857;
            margin: 0 0 4px 0;
            text-align: center;
        }
        h2 {
            font-size: 12pt;
            color: #047857;
            border-bottom: 2px solid #10b981;
            padding-bottom: 4px;
            margin: 18px 0 10px 0;
        }
        .subtitle {
            text-align: center;
            color: #64748b;
            font-size: 9pt;
            margin-bottom: 4px;
        }
        .period {
            text-align: center;
            font-weight: bold;
            margin-bottom: 18px;
            padding: 6px;
            background: #ecfdf5;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }
        table.summary td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
        }
        table.summary td.label {
            background: #f1f5f9;
            font-weight: bold;
            width: 200px;
        }
        table.data {
            font-size: 8.5pt;
        }
        table.data th {
            background: #047857;
            color: white;
            padding: 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #047857;
        }
        table.data td {
            padding: 5px 6px;
            border: 1px solid #cbd5e1;
        }
        table.data tr:nth-child(even) td {
            background: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 8pt;
            font-weight: bold;
        }
        .badge-mekar { background: #fecaca; color: #991b1b; }
        .badge-sangat { background: #fbcfe8; color: #9d174d; }
        .badge-penyemaian { background: #a7f3d0; color: #065f46; }
        .badge-default { background: #e2e8f0; color: #475569; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        .grid-2 {
            width: 100%;
        }
        .grid-2 td {
            width: 50%;
            vertical-align: top;
            padding: 0 5px;
        }
    </style>
</head>
<body>

    <h1>LAPORAN DETEKSI KESEHATAN BUNGA EDELWEISS</h1>
    <p class="subtitle">Sistem Deteksi Berbasis YOLOv11 + MLP</p>
    <p class="period">
        Periode: {{ $from->format('d M Y') }} &mdash; {{ $to->format('d M Y') }}
        ({{ $summary['days'] }} hari)
    </p>

    <h2>Ringkasan</h2>
    <table class="grid-2">
        <tr>
            <td>
                <table class="summary">
                    <tr>
                        <td class="label">Total Deteksi</td>
                        <td><strong>{{ number_format($summary['total']) }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Total Objek</td>
                        <td><strong>{{ number_format($summary['objects']) }}</strong></td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="summary">
                    <tr>
                        <td class="label">Rata-rata per Hari</td>
                        <td><strong>{{ $summary['avg_per_day'] }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Kondisi Dominan</td>
                        <td><strong>{{ str_replace('_', ' ', $summary['dominant'] ?? '-') }}</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <h2>Distribusi Kondisi</h2>
    <table class="summary">
        <tr>
            <td class="label">Mekar</td>
            <td>{{ $byClass['Mekar'] ?? 0 }} objek</td>
            <td class="label">Sangat Mekar</td>
            <td>{{ $byClass['Sangat_Mekar'] ?? 0 }} objek</td>
            <td class="label">Penyemaian</td>
            <td>{{ $byClass['Penyemaian'] ?? 0 }} objek</td>
        </tr>
    </table>

    <h2>Sumber Deteksi</h2>
    <table class="summary">
        <tr>
            <td class="label">Admin / User</td>
            <td>{{ $sourceBreakdown['admin'] }} deteksi</td>
            <td class="label">Pengunjung</td>
            <td>{{ $sourceBreakdown['guest'] }} deteksi</td>
        </tr>
    </table>

    <h2>Detail Deteksi ({{ $detections->count() }} {{ $detections->count() >= 1000 ? 'pertama' : '' }})</h2>

    @if ($detections->isEmpty())
        <p style="text-align:center; color:#94a3b8; padding:20px;">Tidak ada data deteksi pada periode ini.</p>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th style="width:40px;">ID</th>
                    <th style="width:110px;">Tanggal</th>
                    <th>Sumber</th>
                    <th style="width:60px;">Metode</th>
                    <th style="width:50px;">Objek</th>
                    <th>Kondisi Dominan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detections as $d)
                    @php
                        $dominant = $d->dominant_label;
                        $badgeClass = 'badge-default';
                        if ($dominant === 'Mekar') $badgeClass = 'badge-mekar';
                        elseif ($dominant === 'Sangat_Mekar') $badgeClass = 'badge-sangat';
                        elseif ($dominant === 'Penyemaian') $badgeClass = 'badge-penyemaian';
                    @endphp
                    <tr>
                        <td>#{{ $d->id }}</td>
                        <td>{{ $d->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $d->is_guest ? 'Pengunjung' : ($d->user->name ?? '-') }}</td>
                        <td>{{ ucfirst($d->source) }}</td>
                        <td>{{ $d->object_count }}</td>
                        <td>
                            @if ($dominant)
                                <span class="badge {{ $badgeClass }}">{{ str_replace('_', ' ', $dominant) }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Dibuat: {{ $generatedAt->format('d M Y H:i') }} &middot; Sistem Deteksi Kesehatan Bunga Edelweiss
    </div>

</body>
</html>
