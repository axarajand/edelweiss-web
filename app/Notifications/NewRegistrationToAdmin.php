<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Email ke semua admin (yang sudah approved) saat ada user baru mendaftar.
 */
class NewRegistrationToAdmin extends Notification
{
    use Queueable;

    public function __construct(public User $newUser)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('[Edelweiss Detection] Pendaftaran User Baru Menunggu Persetujuan')
            ->greeting("Halo {$notifiable->name},")
            ->line('Ada user baru yang mendaftar dan menunggu persetujuan Anda:')
            ->line("**Nama:** {$this->newUser->name}")
            ->line("**Email:** {$this->newUser->email}")
            ->line("**Tanggal daftar:** {$this->newUser->created_at->format('d M Y H:i')}")
            ->action('Tinjau di Manajemen User', route('admin.users.index', ['status' => 'pending']))
            ->line('Anda dapat menyetujui atau menolak pendaftaran melalui panel admin.');
    }
}
