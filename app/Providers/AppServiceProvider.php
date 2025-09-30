<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS and correct base URL
        //URL::forceScheme('https');
        //URL::forceRootUrl('https://s5407849.elf.ict.griffith.edu.au:8443');
    }
}
