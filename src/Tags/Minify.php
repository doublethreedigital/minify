<?php

namespace DoubleThreeDigital\Minify\Tags;

use Statamic\Tags\Tags;

class Minify extends Tags
{
    protected static $handle = 'minify';

    public function index()
    {
        return 'empty';
    }

    // {{ minify:css }}
    public function css()
    {
        $path = realpath(__DIR__);

        dd(__DIR__);

        return 'hey';
    }

    // {{ minify:js }}
    public function js()
    {
        //
    }

    protected function minify(string $type, string $path)
    {
        // TODO: use this https://github.com/matthiasmullie/minify
    }
}
