<?php

namespace DoubleThreeDigital\Minify\Tests;

use DoubleThreeDigital\Minify\Tags\MinifyTag;
use Illuminate\Support\Facades\Storage;
use Statamic\Facades\Antlers;

class MinifyTagTest extends TestCase
{
    public $tag;

    public function setUp(): void
    {
        parent::setUp();

        $this->tag = (new MinifyTag())
            ->setParser(Antlers::parser())
            ->setContext([]);
    }

    /** @test */
    public function minify_tag_has_been_registered()
    {
        $this->assertTrue(isset($this->app['statamic.tags']['minify']));
    }

    /** @test */
    public function can_minify_css_stylesheet()
    {
        Storage::fake('public');

        $this->tag->setParameters(['src' => '/../resources/css/app.css']);
        $usage = $this->tag->css();

        Storage::disk('public')->assertExists('app.css');

        $this->assertIsString($usage);
        $this->assertSame($usage, '/storage/app.css');
        $this->assertSame(Storage::disk('public')->get('app.css'), file_get_contents(__DIR__.'/__fixtures__/comparison/app.css'));
    }

    /** @test */
    public function can_minify_css_stylesheet_and_return_inline()
    {
        $this->tag->setParameters(['src' => '/../resources/css/app.css', 'inline' => true]);
        $usage = $this->tag->css();

        $this->assertIsString($usage);
        $this->assertStringContainsString('.xl\:focus\:bg-purple-200:focus{background-color:#e9d8fd}.xl\:focus\:bg-purple-300:focus{background-color:#d6bcfa}.xl', $usage);
    }

    /** @test */
    public function can_minify_js_script()
    {
        Storage::fake('public');

        $this->tag->setParameters(['src' => '/../resources/js/app.js']);
        $usage = $this->tag->js();

        Storage::disk('public')->assertExists('app.js');

        $this->assertIsString($usage);
        $this->assertSame($usage, '/storage/app.js');
        $this->assertSame(Storage::disk('public')->get('app.js'), file_get_contents(__DIR__.'/__fixtures__/comparison/app.js'));
    }

    /** @test */
    public function can_minify_js_script_and_return_inline()
    {
        $this->tag->setParameters(['src' => '/../resources/js/app.js', 'inline' => true]);
        $usage = $this->tag->js();

        $this->assertIsString($usage);
        $this->assertStringContainsString('return value};Watcher.prototype.addDep=function addDep(dep){var id=dep.id;', $usage);
    }
}
