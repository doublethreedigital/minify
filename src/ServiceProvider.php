<?php

namespace DoubleThreeDigital\Minify;

use DoubleThreeDigital\Minify\Console\Commands\ClearMinifyCache;
use DoubleThreeDigital\Minify\Tags\MinifyTag;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $commands = [
        ClearMinifyCache::class,
    ];

    protected $tags = [
        MinifyTag::class,
    ];
}
