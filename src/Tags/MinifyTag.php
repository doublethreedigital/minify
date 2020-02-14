<?php

namespace DoubleThreeDigital\Minify\Tags;

use Illuminate\Support\Facades\Storage;
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;
use Statamic\Tags\Tags;

class MinifyTag extends Tags
{
    protected static $handle = 'minify';

    public function css()
    {
        $path = realpath(public_path($this->getParam('src')));

        $minifier = new CSS($path);

        Storage::disk('public')->put('something.css', $minifier->minify());

        return config('filesystems.disks.public.url').'/something.css';
    }

    public function js()
    {
        $path = realpath(public_path($this->getParam('src')));

        $minifier = new JS($path);

        Storage::disk('public')->put('something.js', $minifier->minify());

        return config('filesystems.disks.public.url').'/something.js';
    }
}
