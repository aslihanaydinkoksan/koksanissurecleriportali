<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Kuyruk için önemli
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // İleride buraya 'database' veya 'sms' eklenebilir
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Şifre sıfırlama URL'ini oluşturuyoruz
        // route('password.reset') rotasının tanımlı olduğunu varsayıyorum (Laravel UI/Breeze/Jetstream standartı)
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('KÖKSAN İş Süreçleri Portalı (TAKVİM) - Şifre Sıfırlama Talebi')
            ->greeting('Merhaba ' . $notifiable->name . ',')
            ->line('Hesabınız için bir şifre sıfırlama talebi var.')
            ->action('Şifreyi Sıfırla', $url)
            ->line('Bu işlem için süreniz 60 dakikadır.')
            ->line('Eğer bu talebi siz yapmadıysanız, hiçbir işlem yapmanıza gerek yoktur.')
            ->salutation('İyi çalışmalar.');
    }
    /**
     * VERİTABANI İÇERİĞİ (Arşivlenecek veri)
     * notifications tablosundaki 'data' sütununa JSON olarak yazılır.
     * Yarın öbür gün "Kime, ne zaman, ne gitmiş?" dersen buraya bakacağız.
     */
    public function toArray($notifiable)
    {
        return [
            'action' => 'password_reset', // İşlem türü
            'ip_address' => request()->ip(), // İsteği yapan IP (Güvenlik için)
            'message' => 'Şifre sıfırlama talebi gönderildi.',
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}