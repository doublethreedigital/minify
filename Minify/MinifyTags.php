<?php

namespace Statamic\Addons\Minify;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use MatthiasMullie\Minify;
use Statamic\API\Config;
use Statamic\API\Crypt;
use Statamic\API\Hash;
use Statamic\Extend\Tags;

class MinifyTags extends Tags
{
    public function __construct()
    {
        $this->filesystem = new Filesystem();

        $this->basePath = getcwd();
        $this->publicPath = SITE_ROOT;

        $this->themeName = (new Config())->getThemeName();
        $this->themePath = "$this->basePath/site/themes/$this->themeName";

        $this->output = $this->getConfig('output', '_minify');

        if (! $this->filesystem->exists(getcwd().$this->publicPath.$this->output)) {
            $this->filesystem->makeDirectory(getcwd().$this->publicPath.$this->output);
        }
    }

    /**
     * {{ minify:css }}.
     *
     * @return string
     */
    public function css()
    {
        return $this->minify('css', $this->getParam('src', $this->themeName));
    }

    /**
     * {{ minify:js }}.
     *
     * @return string
     */
    public function js()
    {
        return $this->minify('js', $this->getParam('src', $this->themeName));
    }

    /**
     * Perform minification and return asset link
     * back to the user.
     *
     * @param $type
     * @param $name
     * @return string
     */
    protected function minify(string $type, string $name)
    {
        $sourceFileName = "$name.$type";
        $sourceFilePath = "$this->themePath/$type/$sourceFileName";
        $sourceContents = file_get_contents($sourceFilePath);

        $destinationPath = "$this->basePath$this->publicPath/$this->output/$sourceFileName";
        $destinationUrl = "/$this->output/$sourceFileName";

        if (! $this->hasBeenUpdated($sourceFileName, $sourceContents)) {
            return $destinationUrl;
        }

        file_put_contents($destinationPath, '');

        switch ($type) {
            case 'css':
                $minifier = new Minify\CSS($sourceFilePath);
                $minifier->minify($destinationPath);
                break;

            case 'js':
                $minifier = new Minify\JS($sourceFilePath);
                $minifier->minify($destinationPath);
                break;
        }

        $this->updateAsset($sourceFileName, $sourceContents);

        return $destinationUrl;
    }

    protected function hasBeenUpdated(string $key, string $contents)
    {
        $storedHash = $this->storage->getJSON(Str::slug($key));
        $currentHash = hash('sha256', $contents);

        if (! $storedHash || ! $currentHash) {
            return true;
        }

        return $storedHash === $currentHash;
    }

    protected function updateAsset(string $key, string $contents)
    {
        $this->storage->putJSON(Str::slug($key), hash('sha256', $contents));
    }
}
