<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Detection;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportController extends Controller
{
    /**
     * Build query based on request filters.
     */
    private function buildQuery(Request $request)
    {
        $from = $request->filled('from')
            ? Carbon::parse($request->input('from'))->startOfDay()
            : Carbon::today()->subDays(29);

        $to = $request->filled('to')
            ? Carbon::parse($request->input('to'))->endOfDay()
            : Carbon::today()->endOfDay();

        $query = Detection::query()
            ->with('user')
            ->whereBetween('created_at', [$from, $to]);

        $condition = $request->input('condition');
        if ($condition && in_array($condition, ['Mekar', 'Sangat_Mekar', 'Penyemaian'])) {
            $query->where('result', 'like', '%"label":"' . $condition . '"%');
        }

        $sourceMethod = $request->input('source_method', 'all');
        if (in_array($sourceMethod, ['upload', 'camera'])) {
            $query->where('source', $sourceMethod);
        }

        $userSource = $request->input('user_source', 'all');
        if ($userSource === 'guest') {
            $query->where('is_guest', true);
        } elseif ($userSource === 'admin') {
            $query->where('is_guest', false);
        }

        return [
            'query' => $query,
            'from' => $from,
            'to' => $to,
            'filters' => [
                'condition' => $condition,
                'source_method' => $sourceMethod,
                'user_source' => $userSource,
            ],
        ];
    }

    /**
     * Compute summary statistics.
     */
    private function buildSummary($detections, Carbon $from, Carbon $to): array
    {
        $total = $detections->count();
        $totalObjects = $detections->sum('object_count');
        $daysSpan = max(1, (int) floor($from->diffInDays($to)) + 1);

        $byClass = ['Mekar' => 0, 'Sangat_Mekar' => 0, 'Penyemaian' => 0];
        $confSum = 0;
        $confCount = 0;

        foreach ($detections as $d) {
            foreach ($d->result['detections'] ?? [] as $det) {
                $label = $det['label'] ?? null;
                if (isset($byClass[$label])) {
                    $byClass[$label]++;
                }
                if (isset($det['mlp_confidence'])) {
                    $confSum += $det['mlp_confidence'];
                    $confCount++;
                }
            }
        }

        $dominant = !empty(array_filter($byClass))
            ? collect($byClass)->sortDesc()->keys()->first()
            : null;

        return [
            'total' => $total,
            'total_objects' => $totalObjects,
            'avg_per_day' => round($total / $daysSpan, 1),
            'days' => $daysSpan,
            'by_class' => $byClass,
            'dominant' => $dominant,
            'avg_confidence' => $confCount > 0 ? round(($confSum / $confCount) * 100, 1) : 0,
        ];
    }

    /**
     * Export ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $built = $this->buildQuery($request);
        $detections = $built['query']->latest()->get();
        $summary = $this->buildSummary($detections, $built['from'], $built['to']);

        $data = [
            'detections' => $detections,
            'summary' => $summary,
            'from' => $built['from'],
            'to' => $built['to'],
            'filters' => $built['filters'],
            'generatedAt' => Carbon::now(),
            'generatedBy' => auth()->user()?->name ?? 'System',
            'appName' => config('app.name', 'Edelweiss Detection'),
        ];

        $pdf = Pdf::loadView('exports.report-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);

        $filename = sprintf(
            'Laporan-Deteksi-Edelweis_%s_sd_%s.pdf',
            $built['from']->format('Ymd'),
            $built['to']->format('Ymd')
        );

        return $pdf->download($filename);
    }

    /**
     * Export ke Excel (XLSX) - 1 sheet detail lengkap.
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $built = $this->buildQuery($request);
        $detections = $built['query']->latest()->get();
        $summary = $this->buildSummary($detections, $built['from'], $built['to']);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Detail Deteksi');

        // ===== Header info (row 1-7) =====
        $sheet->setCellValue('A1', 'LAPORAN DETEKSI KESEHATAN BUNGA EDELWEIS');
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Periode:');
        $sheet->setCellValue('B2', $built['from']->format('d M Y') . ' s/d ' . $built['to']->format('d M Y') . ' (' . $summary['days'] . ' hari)');
        $sheet->setCellValue('A3', 'Total Deteksi:');
        $sheet->setCellValue('B3', $summary['total']);
        $sheet->setCellValue('A4', 'Total Objek:');
        $sheet->setCellValue('B4', $summary['total_objects']);
        $sheet->setCellValue('A5', 'Rata-rata per Hari:');
        $sheet->setCellValue('B5', $summary['avg_per_day']);
        $sheet->setCellValue('A6', 'Dihasilkan:');
        $sheet->setCellValue('B6', Carbon::now()->format('d M Y H:i') . ' oleh ' . (auth()->user()?->name ?? 'System'));

        $sheet->getStyle('A2:A6')->getFont()->setBold(true);

        // ===== Table headers (row 8) =====
        $headerRow = 8;
        $headers = [
            'A' => 'ID',
            'B' => 'Tanggal',
            'C' => 'Jam',
            'D' => 'Sumber',
            'E' => 'Metode',
            'F' => 'Kondisi Dominan',
            'G' => 'Jumlah Objek',
            'H' => 'Avg Confidence (%)',
            'I' => 'Detail Objek',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue($col . $headerRow, $label);
        }

        // Style header row: bold, bg green, white text, centered
        $headerRange = 'A' . $headerRow . ':I' . $headerRow;
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '047857']],
            ],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(24);

        // ===== Data rows =====
        $row = $headerRow + 1;
        foreach ($detections as $d) {
            $sheet->setCellValue('A' . $row, $d->id);
            $sheet->setCellValue('B' . $row, $d->created_at->format('d M Y'));
            $sheet->setCellValue('C' . $row, $d->created_at->format('H:i'));
            $sheet->setCellValue('D' . $row, $d->is_guest ? 'Guest' : ($d->user->name ?? '—'));
            $sheet->setCellValue('E' . $row, $d->source === 'camera' ? 'Kamera' : 'Upload');
            $sheet->setCellValue('F' . $row, $d->dominant_label ? str_replace('_', ' ', $d->dominant_label) : '—');
            $sheet->setCellValue('G' . $row, $d->object_count);
            $sheet->setCellValue('H' . $row, round($d->avg_confidence * 100, 1));

            // Build detail objek string
            $detailParts = [];
            foreach ($d->result['detections'] ?? [] as $i => $det) {
                $label = str_replace('_', ' ', $det['label'] ?? '?');
                $conf = round(($det['mlp_confidence'] ?? 0) * 100, 1);
                $detailParts[] = "#" . ($i + 1) . " {$label} ({$conf}%)";
            }
            $sheet->setCellValue('I' . $row, implode(' | ', $detailParts));

            // Zebra striping
            if (($row - $headerRow) % 2 === 0) {
                $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8FAFC'], // slate-50
                    ],
                ]);
            }

            $row++;
        }

        // Empty state
        if ($detections->isEmpty()) {
            $sheet->setCellValue('A' . $row, 'Tidak ada data sesuai filter.');
            $sheet->mergeCells('A' . $row . ':I' . $row);
            $sheet->getStyle('A' . $row)->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'font' => ['italic' => true, 'color' => ['rgb' => '94A3B8']],
            ]);
            $row++;
        }

        $lastDataRow = $row - 1;

        // Border untuk semua data
        if ($lastDataRow >= $headerRow + 1) {
            $sheet->getStyle('A' . ($headerRow + 1) . ':I' . $lastDataRow)
                ->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']],
                    ],
                ]);
        }

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(14);
        $sheet->getColumnDimension('C')->setWidth(8);
        $sheet->getColumnDimension('D')->setWidth(22);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(12);
        $sheet->getColumnDimension('H')->setWidth(18);
        $sheet->getColumnDimension('I')->setWidth(60);

        // Center-align some columns
        $sheet->getStyle('A' . ($headerRow + 1) . ':A' . $lastDataRow)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C' . ($headerRow + 1) . ':C' . $lastDataRow)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E' . ($headerRow + 1) . ':H' . $lastDataRow)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Freeze panes — supaya header tetap visible saat scroll
        $sheet->freezePane('A' . ($headerRow + 1));

        // Auto filter on header
        if ($detections->isNotEmpty()) {
            $sheet->setAutoFilter('A' . $headerRow . ':I' . $lastDataRow);
        }

        // ===== Output as download =====
        $filename = sprintf(
            'Laporan-Deteksi-Edelweis_%s_sd_%s.xlsx',
            $built['from']->format('Ymd'),
            $built['to']->format('Ymd')
        );

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
