<?php

namespace App\Library\Socialite\Facades;

use Illuminate\Support\Facades\Facade;
use App\Library\Socialite\Contracts\Factory;

/**
 * @see \App\Library\Socialite\SocialiteManager
 */
class Socialite extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
