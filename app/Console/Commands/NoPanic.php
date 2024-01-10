<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NoPanic extends Command
{
    protected $signature = 'no_panic';

    protected $description = 'Clears all available Laravel caches! (C) Stefan aus dem letzen MySql/PHP-Kurs ;-)';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->call('view:clear');
        $this->call('route:clear');
        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('view:clear');
        $this->call('event:clear');
        $this->call('optimize:clear');
        $this->call('package:discover');
        $this->info('Cleared all available Laravel caches!');
    }
}