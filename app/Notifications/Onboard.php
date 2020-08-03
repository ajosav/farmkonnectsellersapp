<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class Onboard extends Notification implements ShouldQueue 
{
    use Queueable;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        return $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $name = Str::of($this->user->name)->before(' ');

        return (new MailMessage)
                    ->subject('Welcome To FarmKonnect SellersApp')
                    ->greeting('Hello! '.$name )
                    ->line('Your Email has been successfully verified.')
                    ->line('Welcome to FarmKonnect SellersApp. We will bring you news and updates via the notitification panel on the dashboard.')
                    ->line('If you have any question or clarification, you can simply reach us via email, we will get back to you in no time')
                    ->action('Continue to dashboard', url('/home'))
                    ->line('Please disregard this email is you do not signup on our platform');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $name = Str::of($this->user->name)->before(' ');
        $url = url('/home');
        $message = "
                    Welcome To FarmKonnect SellersApp <br>
                    Hello! {$name} <br>
                    Your Email has been successfully verified. <br>
                    Welcome to FarmKonnect SellersApp. We will bring you news and updates via the notitification panel on the dashboard. <br>
                    If you have any question or clarification, you can simply reach us via email, we will get back to you in no time <br>
                    <a href='{$url}'>Continue to dashboard</a> <br>
                    Please disregard this email is you do not signup on our platform <br>
                    ";
        return [
            'user_uuid' => $this->user->uuid,
            'user_email' => $this->user->email,
            'mail_content' => $message
        ];
    }
}
