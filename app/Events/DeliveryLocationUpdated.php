<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeliveryLocationUpdated implements ShouldBroadcast // laravel 'll send this event to pusher from this class
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $lng;
    public $lat;
    public $delivery;
    /**
     * Create a new event instance.
     */
    public function __construct( $lng, $lat, $delivery)
    {
        $this->lng= $lng;
        $this->lat= $lat;
        $this->delivery= $delivery;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [ // change from private to public
            new PrivateChannel('deliveries.'.$this->delivery->order_id),
        ];
    }
    public function broadcastWith(): array
    {
        return [

            'lng'=>$this->lng,
            'lat'=>$this->lat,
        ];
    }
    public function broadcastAs() // to change the name of the event
    {
        return 'location-updated';
    }
}
