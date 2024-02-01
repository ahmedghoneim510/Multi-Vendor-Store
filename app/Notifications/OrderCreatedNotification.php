<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Broadcast;

class OrderCreatedNotification extends Notification
{
    use Queueable;


    public $order;
    /**
     * Create a new notification instance.
     */
    public function __construct($order)
    {
        $this->order=$order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array // choice the channal to send notification
    {
        //$notifiable is object from the current user (use like mail and not like sms we decide what channel based on it)
        // mail ,database,broadcast
        return ['database'];
        /* we 'll use it later
        $channels=['database']; // default database
        if($notifiable->notification_preference['order_created']['sms'] ??false){
            $channels[]='vonage'; // check if user prefer sms we 'll add it to the channels
        }
        if($notifiable->notification_preference['order_created']['mail']??false){
            $channels[]='mail'; // check if user prefer mail we 'll add it to the channels
        }
        if($notifiable->notification_preference['order_created']['broadcast']??false){
            $channels[]='broadcast'; // check if user prefer broadcast we 'll add it to the channels
        }
        return $channels;*/
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage // $notifiable is the user we get if from db
    {
        $add=$this->order->billingAddress;
        //dd(add);
        return (new MailMessage) // this will redirect to use info in env
                    ->subject("New Order {$this->order->number}") // send number of order
                    ->from('notification@ajyal-store.eg') // if it not exist it 'll use defult in env file
                    ->greeting("Hi {$notifiable->name}") // $user->name
                    ->line("A new order {$this->order->number} has been created by {$add->name} from {$add->country_name}") // paragraph
                    ->action('view order', url('/dashboard')) // redirect
                    ->line('Thank you for using our application!');// paragraph
    }

    public function toDatabase(object $notifiable){
        $add=$this->order->billingAddress;
        return [
          'body'=>"A new order {$this->order->number} has been created by {$add->name} from {$add->country_name}",
            'icon'=>'fas fa-file',
            'url'=>url('/dashboard'),
            'order_id'=>$this->order->id,
        ];

    }
    public function toBroadcast(object $notifiable){
        $add=$this->order->billingAddress; // we should spacifc channal
        // (public=>anyone can see it even he isn't register,
        //private=> specific to user )

        return new BroadcastMessage( [
            'body'=>"A new order {$this->order->number} has been created by {$add->name} from {$add->country_name}",
            'icon'=>'fas fa-file',
            'url'=>url('/dashboard'),
            'order_id'=>$this->order->id,
        ]);
    }
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
