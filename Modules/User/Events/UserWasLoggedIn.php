<?php

namespace Modules\User\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserWasLoggedIn implements ShouldBroadcast
{
    use InteractsWithSockets;
    use SerializesModels;
    use Dispatchable;

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array|PrivateChannel
     */
    public function broadcastOn()
    {
        return new  PrivateChannel('app.events');
    }

    public function broadcastWith()
    {
        return [
            'message' => user()->name . ' just logged in.',
            'time' => Carbon::now()->toTimeString()
        ];
    }
}
