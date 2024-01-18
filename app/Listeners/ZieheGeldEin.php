<?php

namespace App\Listeners;

use App\Events\Kauf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ZieheGeldEin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Kauf $event): void
    {
        //
        logger()->info("Kauf: ZieheGeldEin");
    }
}
