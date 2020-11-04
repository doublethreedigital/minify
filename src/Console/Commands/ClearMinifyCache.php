<?php

namespace DoubleThreeDigital\Minify\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use SplFileInfo;
use Statamic\Console\RunsInPlease;

class ClearMinifyCache extends Command
{
    use RunsInPlease;

    protected $signature = 'minify:clear';
    protected $description = 'Clear your Minify cache.';

    public function handle()
    {
        $this->info('Clearing your Minify cache..');
        
        collect(File::allFiles(config('filesystems.disks.public.root').'/_minify/'))
            ->each(function (SplFileInfo $file) {
                $this->line("Clearing {$file->getFilename()}");
                File::delete($file);
                Cache::forget($file->getFilename());
            });
    }
}
