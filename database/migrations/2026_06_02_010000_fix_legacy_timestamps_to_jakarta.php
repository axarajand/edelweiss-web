<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * KOREKSI DATA LAMA TABEL `detections` (one-time).
 *
 * Sebelum perbaikan, APP timezone = UTC. Akibatnya timestamp deteksi lama
 * tersimpan 7 jam lebih awal dari waktu Jakarta (WIB = UTC+7) — deteksi jam
 * 20:00 WIB tampil jadi 13:00.
 *
 * Migration ini menggeser +7 jam kolom created_at & updated_at pada tabel
 * `detections` untuk baris yang dibuat SEBELUM waktu deploy perbaikan, agar
 * tampil sesuai waktu Jakarta. Data baru tidak tersentuh.
 *
 * Hanya tabel `detections` yang dikoreksi (sumber keluhan). Tabel lain
 * (galleries, researchers, dst.) berisi data seed yang nilainya sudah
 * dimaksudkan sebagai waktu Jakarta, jadi tidak ikut digeser.
 *
 * Hanya berjalan pada MySQL. Aman dari dobel-eksekusi karena Laravel mencatat
 * migration yang sudah dijalankan.
 */
return new class extends Migration
{
    /** Data dengan created_at < batas ini dianggap data lama (tersimpan UTC). */
    private string $cutoff = '2026-06-02 00:00:00';

    /** Batas yang sama setelah digeser +7 jam (untuk rollback). */
    private string $shiftedCutoff = '2026-06-02 07:00:00';

    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql' || !Schema::hasTable('detections')) {
            return;
        }

        DB::table('detections')
            ->where('created_at', '<', $this->cutoff)
            ->update([
                'created_at' => DB::raw('DATE_ADD(created_at, INTERVAL 7 HOUR)'),
                'updated_at' => DB::raw('DATE_ADD(updated_at, INTERVAL 7 HOUR)'),
            ]);
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql' || !Schema::hasTable('detections')) {
            return;
        }

        DB::table('detections')
            ->where('created_at', '<', $this->shiftedCutoff)
            ->update([
                'created_at' => DB::raw('DATE_SUB(created_at, INTERVAL 7 HOUR)'),
                'updated_at' => DB::raw('DATE_SUB(updated_at, INTERVAL 7 HOUR)'),
            ]);
    }
};
