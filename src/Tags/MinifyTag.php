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
        if (! $this->params->has('src')) {
            return $this->minifyInline('css');
        }

        return $this->minify('css');
    }

    public function js()
    {
        if (! $this->params->has('src')) {
            return $this->minifyInline('js');
        }

        return $this->minify('js');
    }

    protected function minify(string $type)
    {   
        $path = realpath(public_path($this->params->get('src')));
        $filename = basename($this->params->get('src'));

        switch ($type) {
            case 'css':
                $minifier = new CSS($path);
                break;

            case 'js':
                $minifier = new JS($path);
                break;
        }

        if ($this->params->has('inline')) {
            return $minifier->minify();
        }

        if (! $this->hasBeenUpdated($filename, file_get_contents($path))) {
            return $this->formUrl($filename, $this->getHash($filename));
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

        return $type === 'css' ? 
            "<style>{$content}</style>" : 
            "<script>{$content}</script>";
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
