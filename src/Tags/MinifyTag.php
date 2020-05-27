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

        if ($this->getParam('inline')) {
            return $minifier->minify();
        }

        Storage::disk('public')->put(basename($this->getParam('src')), $minifier->minify());

        return config('filesystems.disks.public.url').'/'.basename($this->getParam('src'));
    }

    public function js()
    {
        $path = realpath(public_path($this->getParam('src')));

        $minifier = new JS($path);

        if ($this->getParam('inline')) {
            return $minifier->minify();
        }

        Storage::disk('public')->put(basename($this->getParam('src')), $minifier->minify());

        return config('filesystems.disks.public.url').'/'.basename($this->getParam('src'));
    }
}
