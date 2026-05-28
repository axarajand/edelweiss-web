<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detections', function (Blueprint $table) {
            // Relative path: 'detections/2026/05/abc123.jpg'
            // Akses dari web: asset('storage/' . $imagePath)
            $table->string('image_path', 500)->nullable()->after('result');
        });
    }

    public function down(): void
    {
        Schema::table('detections', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};
