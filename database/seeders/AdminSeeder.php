<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Buat admin pertama yang sudah approved otomatis.
     * Pakai: php artisan db:seed --class=AdminSeeder
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@edelweiss.local'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'status' => User::STATUS_APPROVED,
                'approved_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('  Admin pertama berhasil dibuat:');
        $this->command->line('  Email:    admin@edelweiss.local');
        $this->command->line('  Password: admin123');
        $this->command->warn('  Segera ganti password setelah login pertama!');
    }
}
