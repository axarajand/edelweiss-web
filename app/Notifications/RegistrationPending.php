<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationPending extends Notification
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('[Edelweiss Detection] Pendaftaran Diterima - Menunggu Persetujuan')
            ->greeting("Halo {$notifiable->name},")
            ->line('Terima kasih telah mendaftar di Edelweiss Detection &mdash; sistem deteksi kesehatan bunga Edelweis Jawa.')
            ->line('Akun Anda telah dibuat dan sedang **menunggu persetujuan admin**.')
            ->line('Setelah disetujui, Anda akan menerima email pemberitahuan dan dapat login menggunakan email & password yang telah Anda daftarkan.')
            ->line('Proses persetujuan biasanya memakan waktu 1×24 jam pada hari kerja.')
            ->line('Sambil menunggu, Anda tetap bisa menggunakan fitur deteksi publik:')
            ->action('Coba Deteksi Sekarang', route('guest.detection'))
            ->line('Salam, Tim Edelweiss Detection');
    }
}
