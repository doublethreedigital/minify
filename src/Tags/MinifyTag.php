<?php

namespace DoubleThreeDigital\Minify\Tags;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;
use Statamic\Tags\Tags;

class MinifyTag extends Tags
{
    protected static $handle = 'minify';

    public function css()
    {
        if (! $this->getParam('src')) {
            return $this->minifyInline('css');
        }

        return $this->minify('css');
    }

    public function js()
    {
        if (! $this->getParam('src')) {
            return $this->minifyInline('js');
        }

        return $this->minify('js');
    }

    protected function minify(string $type)
    {   
        $path = realpath(public_path($this->getParam('src')));
        $filename = basename($this->getParam('src'));

        if (! $this->hasBeenUpdated($filename, file_get_contents($path))) {
            return $this->formUrl($filename, $this->getHash($filename));
        }

        switch ($type) {
            case 'css':
                $minifier = new CSS($path);
                break;

            case 'js':
                $minifier = new JS($path);
                break;
        }

        if ($this->getParam('inline')) {
            return $minifier->minify();
        }

        Storage::disk('public')->put('_minify/'.$filename, $minifier->minify());

        $this->updateAsset($filename, file_get_contents($path));

        return $this->formUrl($filename, $this->getHash($filename));
    }

    protected function minifyInline(string $type)
    {
        $filename = '_minify/'.uniqid();
        $path = config('filesystems.disks.public.root').'/'.$filename;

        $content = strip_tags($this->content);

        Storage::disk('public')->put($filename, $content);

        switch ($type) {
            case 'css':
                $minifier = new CSS($path);
                break;

            case 'js':
                $minifier = new JS($path);
                break;
        }

        $content = $minifier->minify();

        return "<script>{$content}</script>";
    }

    protected function hasBeenUpdated(string $key, string $contents)
    {
        $storedHash = Cache::get('minify_'.$key);
        $currentHash = hash('sha256', $contents);

        if (! $storedHash || ! $currentHash) {
            return true;
        }

        return $storedHash !== $currentHash;
    }

    protected function updateAsset(string $key, string $contents)
    {
        Cache::put('minify_'.$key, hash('sha256', $contents));
    }

    protected function getHash(string $key)
    {
        return Cache::get('minify_'.$key);
    }

    protected function formUrl(string $name, string $hash)
    {
        return config('filesystems.disks.public.url').'/_minify/'.$name.'?version='.$hash;
    }
}
