<?php

namespace DoubleThreeDigital\Minify;

use DoubleThreeDigital\Minify\Tags\MinifyTag;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        MinifyTag::class,
    ];
}
