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
     * Create a new notification instance.
     */
    public function __construct(public readonly string $token)
    {}

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
		$expire = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');
		 
		return (new MailMessage)
				->subject('Reset Password')
				->greeting('Ciao' . ' '  . $notifiable->name . ',')
				->line('Hai ricevuto questa email perché abbiamo ricevuto una richiesta di reimpostazione della password per il tuo account.')
				->action('Reset Password', $this->resetUrl($notifiable))
				->line("Questo link per la reimpostazione della password scadrà tra $expire minuti.")
				->line('Se non hai richiesto la reimpostazione della password, non sono necessarie ulteriori azioni.')
				->salutation('Lo Staff');
    }
	
	protected function resetUrl(mixed $notifiable): string
    {
        return url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
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
}
