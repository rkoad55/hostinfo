<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain;
use App\History;
use App\Batch;
use DB;
use Queue;

use App\Jobs\ResolveDomain;
use GeoIp2\Database\Reader;



class ResolveBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$domain,$batch_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $batch_id)
    {
        //
        // $this->domain=$domain;
        $this->batch_id=$batch_id;
        // $this->user_id=auth()->user()->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

          
         
          
          $batch=Batch::findOrFail($this->batch_id);
         $jobs = [];

         $n=0;
        foreach ($batch->domain as $domain) {
            # code...
            $jobs[] = new ResolveDomain($domain, $batch->id);

            $n++;
            if($n==100)
            {
                 Queue::bulk($jobs);

                 $jobs = [];
                 $n=0;
            }
        }
         
       

        Queue::bulk($jobs);


        
    }



}
