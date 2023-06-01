<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class userNotif extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

     protected $orderId ;
     protected $userId ;
     protected $imagePath ;
     protected $body ;
     protected $title ;
     protected $routeName ;
     protected $toPage ;
     protected $maker ;
     public function __construct($orderId,$userId,$imagePath,$body,$title,$routeName,$toPage,$maker)
     {
         $this->orderId= $orderId ;
         $this->userId= $userId ;
         $this->imagePath= $imagePath ;
         $this->body= $body ;
         $this->title= $title ;
         $this->routeName= $routeName ;
         $this->toPage= $toPage ;
         $this->maker= $maker ;
     }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'orderId' => $this->orderId,
            'userId' => $this->userId,
            'imagePath' => $this->imagePath,
            'body' => $this->body,
            'title' => $this->title,
            'routeName' => $this->routeName,
            'toPage' => $this->toPage,
            'maker' => $this->maker,
        ];
    }
}
