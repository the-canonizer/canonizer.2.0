<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $app['validator']->extend('valid_captcha', function()
        {
            fwrite(STDOUT, print_r('Bypassing captcha!', TRUE));
            return true;
        });
        
        return $app;
    }
}
