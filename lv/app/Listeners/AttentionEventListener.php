<?php

namespace App\Listeners;

use App\Events\AttentionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttentionEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AttentionEvent  $event
     * @return void
     */
    public function handle(AttentionEvent $event)
    {
        $other_id = $event->user_id;
        var_dump($other_id);
        if ($other_id != null) {
            $user_id = auth()->user()->id;
            $result = DB::insert("insert into attentions (user_id, other_id, created_at) values (?, ?, ?)", [$user_id, $other_id, Carbon::now()]);
            if ($result) {
                return '1';
            }
        }
        return '0';
    }
}
