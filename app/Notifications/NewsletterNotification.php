<?php

namespace App\Notifications;

use App\Mail\NewsletterPersoenlich;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use NotificationChannels\Twitter\TwitterStatusUpdate;
use NotificationChannels\Twitter\TwitterChannel;

class NewsletterNotification extends Notification
{
    use Queueable;
    public $empfaenger;
    /**
     * Create a new notification instance.
     */
    public function __construct($empfaenger)
    {
        //
        $this->empfaenger = $empfaenger;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        echo "via<br>";
        return ['mail', 'database', TwitterChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        echo "mail<br>";
        return (new NewsletterPersoenlich($this->empfaenger))->to($notifiable->email);
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {

        return [
            'Eine Mail wurde gesendet an: ' . $this->empfaenger . " und ein Post auf Twitter!"
        ];
    }

    public function toTwitter($notifiable)
    {
        return new TwitterStatusUpdate('🦌## Unser neuer Newsletter, 😂zum Thema: Reisen  #3');
    }
}
