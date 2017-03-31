<?php

namespace App\Jobs\Feed;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Model\Feed;

class Broadcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//    数组
    private $feed;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($feed)
    {
        //
        $this->feed = $feed;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Feed $model)
    {
        //
        $model->broadcastFeed($this->feed);
    }
}
