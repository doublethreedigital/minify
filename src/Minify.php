<?php

namespace DoubleThreeDigital\Minify;

class Minify
{
    public static function html(string $contents, array $options = [])
    {
        $minifier = new Minifiers\Html($options);
        return $minifier->minify($contents);
    }
}
