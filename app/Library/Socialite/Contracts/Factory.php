<?php

namespace App\Library\Socialite\Contracts;

interface Factory
{
    /**
     * Get an OAuth provider implementation.
     *
     * @param  string  $driver
     * @return \App\Library\Socialite\Contracts\Provider
     */
    public function driver($driver = null);
}
