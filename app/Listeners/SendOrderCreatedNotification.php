<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendOrderCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        //$store = $event->order->store;
        $order = $event->order;

        $user = User::where('store_id', $order->store_id)->first();
//         we use notifiable in model of user so we can send notification to user
        if ($user) {
            $user->notify(new OrderCreatedNotification($order));// it's 'll send user as notifiable
        }



        //notify => if i made a queue it's will depend on where it is in queue
        //notifyNow => it's will be send now even there is a queue

        // if there is more than one user we could use foreach (is not recommended)
        // or use notification facades and used array and notification
//        Notification::send($users,new OrderCreatedNotification($order));

    }
}
