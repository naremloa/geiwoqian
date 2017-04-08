<?php

namespace App\Jobs\Feed;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Model\Feed;

class BroadcastUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    protected $producer_id;
    protected $operate_type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $producer_id, $operate_type)
    {
        //
        $this->user_id = $user_id;
        $this->producer_id = $producer_id;
        $this->operate_type = $operate_type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Feed $model)
    {
        //
        $model->broadcastUpdateFeed($this->user_id, $this->producer_id, $this->operate_type);
    }
}
