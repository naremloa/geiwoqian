<?php

namespace App\Console\Commands\FixData;

use Illuminate\Console\Command;

class User extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixdata:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $models = \App\Model\User::all();
        foreach($models as $model){
//            $model->register_time = $model->create_time;
//            $model->active_time = $model->create_time;
            $model->role = 1;
            $model->save();
            $this->info($model->role);
        }
        $this->info('hello');
    }
}
