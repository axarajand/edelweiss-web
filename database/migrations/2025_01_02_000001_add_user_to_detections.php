<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detections', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')
                ->constrained('users')->nullOnDelete();
            $table->boolean('is_guest')->default(false)->after('user_id');
            $table->string('guest_ip', 45)->nullable()->after('is_guest');
            $table->index(['is_guest', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('detections', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['is_guest', 'created_at']);
            $table->dropColumn(['user_id', 'is_guest', 'guest_ip']);
        });
    }
};
