<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\User;

class AttentionEvent implements ShouldBroadcast 
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *    指定事件被广播到哪些频道
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('UserAttention.' . $this->user_id);
    }

    /**
     * 指定广播数据
     *
     * @return array
     */
    public function broadcastWith()
    {
        $id = auth()->user()->id;
        $data = User::find($id, ['name', 'sex', 'area'])->toArray();
        return json_encode($data);
    }
}
