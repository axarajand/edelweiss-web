<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PageController extends Controller
{
    private function countByClass(?Carbon $from = null, ?Carbon $to = null): array
    {
        $byClass = [
            'Mekar' => 0,
            'Sangat_Mekar' => 0,
            'Penyemaian' => 0,
        ];

        $query = Detection::query();
        if ($from) $query->where('created_at', '>=', $from);
        if ($to) $query->where('created_at', '<=', $to);

        if ($query->count() === 0) {
            return $byClass;
        }

        $query->latest()->take(5000)->get()->each(function ($d) use (&$byClass) {
            foreach ($d->result['detections'] ?? [] as $det) {
                if (isset($byClass[$det['label']])) {
                    $byClass[$det['label']]++;
                }
            }
        });

        return $byClass;
    }

    private function trendByDay(int $days = 30): array
    {
        $start = Carbon::today()->subDays($days - 1);

        $rows = Detection::where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $result = [];
        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::today()->subDays($days - 1 - $i)->format('Y-m-d');
            $result[] = [
                'date' => $date,
                'label' => Carbon::parse($date)->format('d M'),
                'count' => (int) ($rows[$date] ?? 0),
            ];
        }

        return $result;
    }

    private function detectionByHour(?Carbon $from = null, ?Carbon $to = null): array
    {
        $query = Detection::query();
        if ($from) $query->where('created_at', '>=', $from);
        if ($to) $query->where('created_at', '<=', $to);

        $rows = $query->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        $result = [];
        for ($h = 0; $h < 24; $h++) {
            $result[] = [
                'hour' => $h,
                'label' => sprintf('%02d:00', $h),
                'count' => (int) ($rows[$h] ?? 0),
            ];
        }

        return $result;
    }

    private function sourceBreakdown(?Carbon $from = null, ?Carbon $to = null): array
    {
        $query = Detection::query();
        if ($from) $query->where('created_at', '>=', $from);
        if ($to) $query->where('created_at', '<=', $to);

        return [
            'admin' => (clone $query)->where('is_guest', false)->count(),
            'guest' => (clone $query)->where('is_guest', true)->count(),
        ];
    }

    public function dashboard()
    {
        return view('pages.dashboard', [
            'totalDetections' => Detection::count(),
            'totalObjects' => Detection::sum('object_count'),
            'recent' => Detection::latest()->take(5)->get(),
            'byClass' => $this->countByClass(),

            'chartTrend7' => $this->trendByDay(7),
            'chartTrend30' => $this->trendByDay(30),
            'chartTrend90' => $this->trendByDay(90),
            'chartHourly' => $this->detectionByHour(),
            'chartSource' => $this->sourceBreakdown(),
        ]);
    }

    public function detection()
    {
        return view('pages.detection');
    }

    public function history(Request $request)
    {
        $query = Detection::query()->with('user');

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->input('to'));
        }

        $sourceFilter = $request->input('user_source', 'all');
        if ($sourceFilter === 'guest') {
            $query->where('is_guest', true);
        } elseif ($sourceFilter === 'admin') {
            $query->where('is_guest', false);
        }

        $inputMethod = $request->input('input_method', 'all');
        if (in_array($inputMethod, ['upload', 'camera'])) {
            $query->where('source', $inputMethod);
        }

        $condition = $request->input('condition');
        if ($condition && in_array($condition, ['Mekar', 'Sangat_Mekar', 'Penyemaian'])) {
            $query->where('result', 'like', '%"label":"' . $condition . '"%');
        }

        if ($request->filled('search')) {
            $query->where('id', $request->input('search'));
        }

        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $detections = $query->paginate(12)->withQueryString();

        // FIX: stats sekarang punya 'admin' (sebelumnya 'camera')
        $stats = [
            'total' => Detection::count(),
            'today' => Detection::whereDate('created_at', today())->count(),
            'admin' => Detection::where('is_guest', false)->count(),
            'guest' => Detection::where('is_guest', true)->count(),
        ];

        return view('pages.history', [
            'detections' => $detections,
            'filters' => [
                'from' => $request->input('from'),
                'to' => $request->input('to'),
                'user_source' => $sourceFilter,
                'input_method' => $inputMethod,
                'condition' => $condition,
                'sort' => $sort,
                'search' => $request->input('search'),
            ],
            'stats' => $stats,
            'byClass' => $this->countByClass(),
        ]);
    }

    public function historyDetail(Detection $detection)
    {
        $detection->load('user');
        return view('pages.history-detail', compact('detection'));
    }

    public function historyDestroy(Detection $detection)
    {
        if ($detection->image_path && Storage::disk('public')->exists($detection->image_path)) {
            Storage::disk('public')->delete($detection->image_path);
        }

        $detection->delete();

        return redirect()->route('admin.history')
            ->with('success', "Deteksi #{$detection->id} berhasil dihapus.");
    }

    public function historyDestroyBatch(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:detections,id',
        ]);

        $detections = Detection::whereIn('id', $request->input('ids'))->get();
        $count = $detections->count();

        foreach ($detections as $detection) {
            if ($detection->image_path && Storage::disk('public')->exists($detection->image_path)) {
                Storage::disk('public')->delete($detection->image_path);
            }
            $detection->delete();
        }

        return redirect()->route('admin.history')
            ->with('success', "{$count} deteksi berhasil dihapus.");
    }

    public function learning()
    {
        return view('pages.learning');
    }

    /**
     * FIX: reports() sekarang implementasi LENGKAP — kirim $filters, $summary, etc.
     */
    public function reports(Request $request)
    {
        $from = $request->filled('from')
            ? Carbon::parse($request->input('from'))->startOfDay()
            : Carbon::today()->subDays(29);

        $to = $request->filled('to')
            ? Carbon::parse($request->input('to'))->endOfDay()
            : Carbon::today()->endOfDay();

        $condition = $request->input('condition');
        $sourceMethod = $request->input('source_method', 'all');
        $userSource = $request->input('user_source', 'all');

        $query = Detection::query()
            ->whereBetween('created_at', [$from, $to]);

        if ($condition && in_array($condition, ['Mekar', 'Sangat_Mekar', 'Penyemaian'])) {
            $query->where('result', 'like', '%"label":"' . $condition . '"%');
        }

        if (in_array($sourceMethod, ['upload', 'camera'])) {
            $query->where('source', $sourceMethod);
        }

        if ($userSource === 'guest') {
            $query->where('is_guest', true);
        } elseif ($userSource === 'admin') {
            $query->where('is_guest', false);
        }

        $detections = (clone $query)->latest()->paginate(20)->withQueryString();

        $totalDetections = (clone $query)->count();
        $totalObjects = (clone $query)->sum('object_count');
        // Fix floating point dengan floor() + int cast
        $daysSpan = max(1, (int) floor($from->diffInDays($to)) + 1);
        $avgPerDay = round($totalDetections / $daysSpan, 1);

        $byClass = $this->countByClass($from, $to);
        $dominantLabel = !empty(array_filter($byClass))
            ? collect($byClass)->sortDesc()->keys()->first()
            : null;

        $trendDaily = [];
        $current = $from->copy()->startOfDay();
        $endDate = $to->copy()->startOfDay();

        $tempRows = (clone $query)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        while ($current->lte($endDate)) {
            $dateStr = $current->format('Y-m-d');
            $trendDaily[] = [
                'date' => $dateStr,
                'label' => $current->format('d M'),
                'count' => (int) ($tempRows[$dateStr] ?? 0),
            ];
            $current->addDay();

            if (count($trendDaily) >= 365) break;
        }

        $sourceBreakdown = [
            'admin' => (clone $query)->where('is_guest', false)->count(),
            'guest' => (clone $query)->where('is_guest', true)->count(),
        ];

        return view('pages.reports', [
            'detections' => $detections,
            'filters' => [
                'from' => $from->format('Y-m-d'),
                'to' => $to->format('Y-m-d'),
                'condition' => $condition,
                'source_method' => $sourceMethod,
                'user_source' => $userSource,
            ],
            'summary' => [
                'total' => $totalDetections,
                'objects' => $totalObjects,
                'avg_per_day' => $avgPerDay,
                'dominant' => $dominantLabel,
                'days' => $daysSpan,
            ],
            'byClass' => $byClass,
            'trendDaily' => $trendDaily,
            'sourceBreakdown' => $sourceBreakdown,
        ]);
    }

    /**
     * Helper: build filter query dari request (sama dengan reports method)
     */
    private function buildReportsQuery(Request $request)
    {
        $from = $request->filled('from')
            ? \Carbon\Carbon::parse($request->input('from'))->startOfDay()
            : \Carbon\Carbon::today()->subDays(29);

        $to = $request->filled('to')
            ? \Carbon\Carbon::parse($request->input('to'))->endOfDay()
            : \Carbon\Carbon::today()->endOfDay();

        $condition = $request->input('condition');
        $sourceMethod = $request->input('source_method', 'all');
        $userSource = $request->input('user_source', 'all');

        $query = Detection::query()->whereBetween('created_at', [$from, $to]);

        if ($condition && in_array($condition, ['Mekar', 'Sangat_Mekar', 'Penyemaian'])) {
            $query->where('result', 'like', '%"label":"' . $condition . '"%');
        }

        if (in_array($sourceMethod, ['upload', 'camera'])) {
            $query->where('source', $sourceMethod);
        }

        if ($userSource === 'guest') {
            $query->where('is_guest', true);
        } elseif ($userSource === 'admin') {
            $query->where('is_guest', false);
        }

        return [
            'query' => $query,
            'from' => $from,
            'to' => $to,
            'condition' => $condition,
            'sourceMethod' => $sourceMethod,
            'userSource' => $userSource,
        ];
    }

    /**
     * Export laporan ke PDF.
     */
    public function reportsExportPdf(Request $request)
    {
        $ctx = $this->buildReportsQuery($request);
        $query = $ctx['query'];
        $from = $ctx['from'];
        $to = $ctx['to'];

        $detections = (clone $query)->latest()->limit(1000)->get();

        $totalDetections = (clone $query)->count();
        $totalObjects = (clone $query)->sum('object_count');
        $daysSpan = max(1, (int) floor($from->diffInDays($to)) + 1);
        $avgPerDay = round($totalDetections / $daysSpan, 1);

        $byClass = $this->countByClass($from, $to);
        $dominantLabel = !empty(array_filter($byClass))
            ? collect($byClass)->sortDesc()->keys()->first()
            : '-';

        $sourceBreakdown = [
            'admin' => (clone $query)->where('is_guest', false)->count(),
            'guest' => (clone $query)->where('is_guest', true)->count(),
        ];

        $data = [
            'detections' => $detections,
            'from' => $from,
            'to' => $to,
            'summary' => [
                'total' => $totalDetections,
                'objects' => $totalObjects,
                'avg_per_day' => $avgPerDay,
                'dominant' => $dominantLabel,
                'days' => $daysSpan,
            ],
            'byClass' => $byClass,
            'sourceBreakdown' => $sourceBreakdown,
            'filters' => [
                'condition' => $ctx['condition'],
                'source_method' => $ctx['sourceMethod'],
                'user_source' => $ctx['userSource'],
            ],
            'generatedAt' => \Carbon\Carbon::now(),
        ];

        $pdf = Pdf::loadView('exports.reports-pdf', $data)
            ->setPaper('a4', 'portrait');

        $filename = 'laporan-edelweiss-' . $from->format('Ymd') . '-' . $to->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export laporan ke Excel (xlsx).
     */
    public function reportsExportExcel(Request $request)
    {
        $ctx = $this->buildReportsQuery($request);
        $query = $ctx['query'];
        $from = $ctx['from'];
        $to = $ctx['to'];

        $detections = (clone $query)->latest()->limit(5000)->get();

        $totalDetections = (clone $query)->count();
        $totalObjects = (clone $query)->sum('object_count');
        $daysSpan = max(1, (int) floor($from->diffInDays($to)) + 1);
        $avgPerDay = round($totalDetections / $daysSpan, 1);

        $byClass = $this->countByClass($from, $to);
        $dominantLabel = !empty(array_filter($byClass))
            ? collect($byClass)->sortDesc()->keys()->first()
            : '-';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan');

        // Title
        $sheet->setCellValue('A1', 'LAPORAN DETEKSI KESEHATAN BUNGA EDELWEISS');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Periode: ' . $from->format('d M Y') . ' s/d ' . $to->format('d M Y') . ' (' . $daysSpan . ' hari)');
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Dibuat: ' . \Carbon\Carbon::now()->format('d M Y H:i'));
        $sheet->mergeCells('A3:F3');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Summary
        $sheet->setCellValue('A5', 'RINGKASAN');
        $sheet->getStyle('A5')->getFont()->setBold(true);

        $sheet->setCellValue('A6', 'Total Deteksi');
        $sheet->setCellValue('B6', $totalDetections);
        $sheet->setCellValue('A7', 'Total Objek');
        $sheet->setCellValue('B7', $totalObjects);
        $sheet->setCellValue('A8', 'Rata-rata per Hari');
        $sheet->setCellValue('B8', $avgPerDay);
        $sheet->setCellValue('A9', 'Kondisi Dominan');
        $sheet->setCellValue('B9', str_replace('_', ' ', $dominantLabel));

        // Distribusi
        $sheet->setCellValue('A11', 'DISTRIBUSI KONDISI');
        $sheet->getStyle('A11')->getFont()->setBold(true);
        $sheet->setCellValue('A12', 'Mekar');
        $sheet->setCellValue('B12', $byClass['Mekar'] ?? 0);
        $sheet->setCellValue('A13', 'Sangat Mekar');
        $sheet->setCellValue('B13', $byClass['Sangat_Mekar'] ?? 0);
        $sheet->setCellValue('A14', 'Penyemaian');
        $sheet->setCellValue('B14', $byClass['Penyemaian'] ?? 0);

        // Detail tabel header
        $startRow = 16;
        $sheet->setCellValue('A' . $startRow, 'DETAIL DETEKSI');
        $sheet->getStyle('A' . $startRow)->getFont()->setBold(true);

        $headerRow = $startRow + 1;
        $headers = ['ID', 'Tanggal', 'Sumber', 'Metode', 'Total Objek', 'Kondisi Dominan'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . $headerRow, $h);
            $sheet->getStyle($col . $headerRow)->getFont()->setBold(true);
            $sheet->getStyle($col . $headerRow)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('D1FAE5');
            $col++;
        }

        // Detail data
        $row = $headerRow + 1;
        foreach ($detections as $d) {
            $sheet->setCellValue('A' . $row, $d->id);
            $sheet->setCellValue('B' . $row, $d->created_at->format('d M Y H:i'));
            $sheet->setCellValue('C' . $row, $d->is_guest ? 'Pengunjung' : ($d->user->name ?? '-'));
            $sheet->setCellValue('D' . $row, ucfirst($d->source));
            $sheet->setCellValue('E' . $row, $d->object_count);
            $sheet->setCellValue('F' . $row, str_replace('_', ' ', $d->dominant_label ?? '-'));
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'F') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        // Border untuk tabel detail
        $lastRow = $row - 1;
        if ($lastRow >= $headerRow) {
            $sheet->getStyle('A' . $headerRow . ':F' . $lastRow)
                ->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
        }

        $filename = 'laporan-edelweiss-' . $from->format('Ymd') . '-' . $to->format('Ymd') . '.xlsx';

        // Tulis ke temp file lalu kirim via response()->download()
        // Lebih reliable di production (Nginx buffering, FPM streaming, dll)
        $tmpFile = tempnam(sys_get_temp_dir(), 'export_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tmpFile);

        return response()->download($tmpFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0, no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ])->deleteFileAfterSend(true);
    }
}
