<?php

namespace DoubleThreeDigital\Minify\Tests;

use DoubleThreeDigital\Minify\Tags\MinifyTag;
use Illuminate\Support\Facades\Storage;
use Statamic\Facades\Antlers;

class MinifyTagTest extends TestCase
{
    public MinifyTag $tag;

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

        Storage::disk('public')->assertExists('_minify/app.css');

        $this->assertIsString($usage);
        $this->assertStringContainsString('/storage/_minify/app.css?version=', $usage);
        $this->assertSame(Storage::disk('public')->get('_minify/app.css'), file_get_contents(__DIR__.'/__fixtures__/comparison/app.css'));
    }

    /** @test */
    public function can_minify_css_stylesheet_and_return_inline()
    {
        Storage::fake('public');

        $this->tag->setParameters(['src' => '/../resources/css/app.css', 'inline' => true]);
        $usage = $this->tag->css();

        $this->assertIsString($usage);
        $this->assertStringContainsString('.xl\:focus\:bg-purple-200:focus{background-color:#e9d8fd}.xl\:focus\:bg-purple-300:focus{background-color:#d6bcfa}.xl', $usage);
    }

    /** @test */
    public function can_minify_css_styles_inline()
    {
        $this->tag->setParameters([]);
        $this->tag->setContent("<style>
            body {
                background: green;
                color: yellow;
            }

            h2 {
                font-size: 21px;
            }
        </style>");
        $usage = $this->tag->css();

        $this->assertIsString($usage);
        $this->assertStringContainsString('<style>', $usage);
        $this->assertStringContainsString('</style>', $usage);
        $this->assertStringContainsString('body{background:green;color:yellow}h2{font-size:21px}', $usage);
    }

    /** @test */
    public function can_minify_js_script()
    {
        $this->markTestIncomplete();

        Storage::fake('public');

        $this->tag->setParameters(['src' => '/../resources/js/app.js']);
        $usage = $this->tag->js();

        Storage::disk('public')->assertExists('_minify/app.js');

        $this->assertIsString($usage);
        $this->assertStringContainsString('/storage/_minify/app.js?version=', $usage);
        $this->assertSame(Storage::disk('public')->get('_minify/app.js'), file_get_contents(__DIR__.'/__fixtures__/comparison/app.js'));
    }

    /** @test */
    public function can_minify_js_script_and_return_inline()
    {
        Storage::fake('public');

        $this->tag->setParameters(['src' => '/../resources/js/app.js', 'inline' => true]);
        $usage = $this->tag->js();

        $this->assertIsString($usage);
        // $this->assertStringContainsString('return value};Watcher.prototype.addDep=function addDep(dep){var id=dep.id;', $usage);
    }

    /** @test */
    public function can_minify_js_styles_inline()
    {
        $this->tag->setParameters([]);
        $this->tag->setContent("<script>
            alert('something');
            console.log('something else');
        </script>");
        $usage = $this->tag->js();

        $this->assertIsString($usage);
        $this->assertStringContainsString('<script>', $usage);
        $this->assertStringContainsString('</script>', $usage);
        $this->assertStringContainsString("alert('something');console.log('something else')", $usage);
    }
}
