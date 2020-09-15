<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class newLike extends Notification
{
    use Queueable;

    public $tweet;

    public function __construct($tweet)
    {
        $this->tweet = $tweet;
    }

    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->tweet->user_id,
            'tweet_id' => $this->tweet->tweet_id,
            'name' => $this->tweet->name,
            'username' => $this->tweet->username,
        ];
    }
    public function toBroadCast($notifiable){
        return new BroadcastMessage([
            'user_id' => $this->tweet->user_id,
            'tweet_id' => $this->tweet->tweet_id,
            'name' => $this->tweet->name,
            'username' => $this->tweet->username,
        ]);
    }
}
