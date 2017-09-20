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
    public $type;

    /**
     * Create a new event instance.
     *  type:0 表示关注通知  1:表示信件通知
     * @return void
     */
    public function __construct($type = 0, $user_id = 0)
    {

        $this->user_id = $user_id;
        $this->type = $type;
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

    // /**
    //  * 指定广播数据
    //  *
    //  * @return array
    //  */
    // public function broadcastWith()
    // {
    //     // $id = auth()->user()->id;
    //     // $data = User::find($id, ['name', 'sex', 'area'])->toArray();
    //     // if ($this->type == 0) {
    //     //     $data['msg'] = '关注你啦';
    //     // } else if ($this->type == 1) {
    //     //     $data['msg'] = '给你写信啦';
    //     // }
    //     return json_encode($data);
    // }
}
