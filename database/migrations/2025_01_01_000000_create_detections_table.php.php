<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detections', function (Blueprint $table) {
            $table->id();
            $table->string('source')->default('upload'); // upload | camera
            $table->unsignedInteger('object_count');
            $table->json('result'); // simpan boxes + labels lengkap
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detections');
    }
};