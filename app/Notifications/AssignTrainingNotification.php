<?php
   
namespace App\Notifications;
   
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
   
class AssignTrainingNotification extends Notification
{
    use Queueable;
  
    private $details;
   
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }
   
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }
   
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->greeting($this->details['greeting'])
    //                 ->line($this->details['body'])
    //                 ->action($this->details['actionText'], $this->details['actionURL'])
    //                 ->line($this->details['thanks']);
    // }
  
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'greeting' => $this->details['greeting'],
            'body' => $this->details['body'],
            'actionText' => $this->details['actionText'],
            'actionURL' => $this->details['actionURL'],
            'training_id' => $this->details['training_id'],
        ];
    }
}