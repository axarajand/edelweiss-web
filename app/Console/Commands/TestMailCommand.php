<?php

namespace App\Console\Commands;

use App\Mail\TestEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    protected $signature = 'mail:test {recipient : Email tujuan untuk test}';

    protected $description = 'Kirim email test untuk memastikan konfigurasi SMTP berfungsi';

    public function handle(): int
    {
        $recipient = $this->argument('recipient');

        $this->info("Mengirim email test ke: {$recipient}");
        $this->newLine();

        $this->line("Konfigurasi yang digunakan:");
        $this->line("  Mailer:   " . config('mail.default'));
        $this->line("  Host:     " . config('mail.mailers.smtp.host'));
        $this->line("  Port:     " . config('mail.mailers.smtp.port'));
        $this->line("  Username: " . config('mail.mailers.smtp.username'));
        $this->line("  From:     " . config('mail.from.address'));
        $this->newLine();

        try {
            Mail::raw(
                "Halo!\n\nIni email test dari Edelweiss Detection.\n\n" .
                "Jika Anda menerima email ini, artinya konfigurasi SMTP " .
                "sudah benar dan email notifikasi sistem akan berfungsi.\n\n" .
                "Waktu kirim: " . now()->format('d M Y H:i:s') . "\n\n" .
                "Salam,\nTim Edelweiss Detection",
                function ($message) use ($recipient) {
                    $message->to($recipient)
                            ->subject('[Edelweiss Detection] Test Email');
                }
            );

            $this->info("✓ Email berhasil dikirim ke {$recipient}");
            $this->newLine();
            $this->line("Silakan cek inbox (dan folder spam!) email tujuan.");
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("✗ Gagal mengirim email!");
            $this->newLine();
            $this->error("Error: " . $e->getMessage());
            $this->newLine();
            $this->warn("Troubleshooting:");
            $this->line("  1. Pastikan MAIL_PASSWORD adalah App Password (16 karakter, tanpa spasi)");
            $this->line("  2. Pastikan MAIL_USERNAME sama dengan MAIL_FROM_ADDRESS");
            $this->line("  3. Pastikan akun Gmail sudah aktif 2FA");
            $this->line("  4. Jalankan: php artisan config:clear");
            $this->line("  5. Cek koneksi internet & firewall (port 587)");
            return self::FAILURE;
        }
    }
}
