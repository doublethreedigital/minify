<?php

namespace Statamic\Addons\Minify;

use Illuminate\Filesystem\Filesystem;
use MatthiasMullie\Minify;
use Statamic\API\Config;
use Statamic\API\Crypt;
use Statamic\Extend\Tags;

class MinifyTags extends Tags
{
    /**
     * MinifyTags constructor.
     */
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
    public function minify($type, $name)
    {
        $sourceFilename = $name.'.'.$type;
        $sourcePath = "$this->themePath/$type/$sourceFilename";
        $hash = Crypt::encrypt($sourceFilename);

        $minifiedPath = "$this->basePath$this->publicPath/$this->output/$hash.$type";
        file_put_contents($minifiedPath, '');

        if ($type = 'css') {
            $minifier = new Minify\CSS($sourcePath);
            $minifier->minify($minifiedPath);
        } elseif ($type = 'js') {
            $minifier = new Minify\JS($sourcePath);
            $minifier->minify($minifiedPath);
        }

        return '/'.$this->output.'/'.$hash.'.'.$type;
    }
}
