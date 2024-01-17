<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogAusgabe implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $i;
    /**
     * Create a new job instance.
     */
    public function __construct($i)
    {
        //
        $this->i = $i;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        logger()->info("Aufgabe mit Warteschlange - asyncron - hoheprioritaet - " . $this->i);
    }
}
