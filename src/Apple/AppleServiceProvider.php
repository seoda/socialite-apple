<?php

namespace Seoda\Socialite\Apple;

use Laravel\Socialite\SocialiteServiceProvider;
use Laravel\Socialite\Contracts\Factory;

use Socialite;

class AppleServiceProvider extends SocialiteServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        Socialite::extend('apple', function ($app) {
            $config = $this->app['config']['services.apple'];

            return Socialite::buildProvider(AppleProvider::class, $config);
        });
    }
}
