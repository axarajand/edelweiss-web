<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('[Edelweiss Detection] Akun Anda Telah Disetujui!')
            ->greeting("Selamat {$notifiable->name}!")
            ->line('Akun Anda telah **disetujui** oleh admin.')
            ->line('Anda sekarang dapat login dan mengakses panel admin Edelweiss Detection dengan fitur lengkap:')
            ->line('• Dashboard statistik real-time')
            ->line('• Manajemen dataset training')
            ->line('• Riwayat deteksi kesehatan & laporan')
            ->line('• Pembelajaran kondisi kesehatan Edelweis')
            ->action('Login Sekarang', route('admin.login'))
            ->line('Selamat berkontribusi untuk konservasi *Anaphalis javanica*!')
            ->line('Salam, Tim Edelweiss Detection');
    }
}
