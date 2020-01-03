<?php

namespace DoubleThreeDigital\Minify;

use DoubleThreeDigital\Minify\Tags\Minify;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        Minify::class,
    ];

    public function boot()
    {
        parent::boot();
    }
}
