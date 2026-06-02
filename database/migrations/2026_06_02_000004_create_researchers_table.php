<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // R&D sekarang 1 konten (showcase project). Tabel multi-artikel lama tidak dipakai.
        Schema::dropIfExists('researches');

        Schema::create('researchers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();        // mis. Ketua Peneliti, Anggota
            $table->string('affiliation')->nullable();  // mis. Dosen / Mahasiswa, institusi
            $table->string('photo_path')->nullable();
            $table->string('scholar_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('researchers');
    }
};
