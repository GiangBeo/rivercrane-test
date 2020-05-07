<?php

namespace App\Jobs;

use App\Log;
use App\MstOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $dataChange;

    /**
     * Create a new job instance.
     *
     * @param MstOrder $order
     */
    public function __construct(MstOrder $order, array $dataChange)
    {
        $this->order = $order;
        $this->dataChange = $dataChange;
    }

    /**
     * @return void
     */
    public function handle()
    {
       $log = new Log;
       $log->changes = $this->dataChange;
       $this->order->logs()->save($log);
    }
}
