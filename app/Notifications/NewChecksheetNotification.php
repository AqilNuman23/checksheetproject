<?php

namespace App\Notifications;

use App\Models\Checksheet;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewChecksheetNotification extends Notification
{
    use Queueable;

    protected $qe;
    protected $checksheet;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $qe, Checksheet $checksheet)
    {
        $this->qe = $qe;
        $this->checksheet = $checksheet;
    }

    /**
     * Get the notification's delivery channels.
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
        return (new MailMessage)
            ->subject('New Checksheet Notification')
            ->greeting('Hello ' . $this->qe->name . ',')
            ->line('A new checksheet has been created.')
            ->action('View Checksheet', url('/checksheets/' . $this->checksheet->id))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}