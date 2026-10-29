<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Token de redefinição de senha.
     */
    private string $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $t)
    {
        $this->token = $t;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Constrói a URL para o frontend (ex: React, Vue, App Móvel) que processará a redefinição
        $frontendUrl = config('app.frontend_url', 'http://localhost:4200') . '/reset-password?token=' . $this->token . '&email=' . urlencode($notifiable->email);

        return (new MailMessage)
            ->subject('Recuperação de Senha - Sistema de Decoração')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Recebemos um pedido para redefinir a senha da sua conta de utilizador.')
            ->action('Redefinir Senha', $frontendUrl)
            ->line('Este link expira em 60 minutos.')
            ->line('Se não solicitou a alteração de senha, nenhuma ação é necessária.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    /**
     * Constrói a mensagem utilizando o template HTML Blade.
     */
    public function toMailForFuture(object $notifiable): MailMessage
    {
        // Monta a URL que será enviada para o botão
        $frontendUrl = config('app.frontend_url', 'http://localhost:3000') . '/reset-password?token=' . $this->token . '&email=' . urlencode($notifiable->email);

        return (new MailMessage)
            ->subject('Recuperação de Senha - DecorAção App')
            ->view('emails.reset-password', [
                'resetUrl' => $frontendUrl,
                'userName' => $notifiable->name ?? 'Utilizador'
            ]);
    }
}
