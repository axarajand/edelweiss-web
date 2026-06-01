<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Detection extends Model
{
    protected $fillable = [
        'user_id',
        'is_guest',
        'guest_ip',
        'source',
        'object_count',
        'result',
        'image_path',
    ];

    protected $casts = [
        'result' => 'array',
        'is_guest' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * URL gambar yang bisa diakses dari browser.
     * Butuh `php artisan storage:link` agar `public/storage` ada.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }
        return asset('storage/' . $this->image_path);
    }

    /**
     * Label kondisi yang paling sering muncul di hasil deteksi.
     * Pakai untuk display badge dominan di galeri.
     */
    public function getDominantLabelAttribute(): ?string
    {
        $detections = $this->result['detections'] ?? [];
        if (empty($detections)) {
            return null;
        }

        $counts = [];
        foreach ($detections as $det) {
            $label = $det['label'] ?? null;
            if ($label) {
                $counts[$label] = ($counts[$label] ?? 0) + 1;
            }
        }

        if (empty($counts)) {
            return null;
        }

        arsort($counts);
        return array_key_first($counts);
    }

    /**
     * Rata-rata MLP confidence dari semua objek.
     */
    public function getAvgConfidenceAttribute(): float
    {
        $detections = $this->result['detections'] ?? [];
        if (empty($detections)) {
            return 0;
        }

        $total = 0;
        $count = 0;
        foreach ($detections as $det) {
            $total += $det['mlp_confidence'] ?? 0;
            $count++;
        }

        return $count > 0 ? $total / $count : 0;
    }

    /**
     * Breakdown jumlah objek per label, di-sort dari yang terbanyak.
     * Dipakai untuk tampilkan multi badge compact di card riwayat.
     *
     * Contoh return:
     *   [
     *     ['label' => 'Mekar', 'count' => 3],
     *     ['label' => 'Penyemaian', 'count' => 1],
     *   ]
     */
    public function getLabelBreakdownAttribute(): array
    {
        $detections = $this->result['detections'] ?? [];
        if (empty($detections)) {
            return [];
        }

        $counts = [];
        foreach ($detections as $det) {
            $label = $det['label'] ?? null;
            if ($label) {
                $counts[$label] = ($counts[$label] ?? 0) + 1;
            }
        }

        if (empty($counts)) {
            return [];
        }

        // Sort by count descending (terbanyak duluan)
        arsort($counts);

        // Convert ke array of associative
        $result = [];
        foreach ($counts as $label => $count) {
            $result[] = ['label' => $label, 'count' => $count];
        }

        return $result;
    }
}
