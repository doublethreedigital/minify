<?php

namespace DoubleThreeDigital\Minify\Tests;

use Statamic\Facades\Parse;

class MinifyTagTest extends TestCase
{
    private function tag($tag)
    {
        return Parse::template($tag, []);
    }

    /** @test */
    public function css_tag_returns_url_with_minified_content()
    {
        $tag = $this->tag('{{ minify:css }}');
    }

    /** @test */
    public function js_tag_returns_url_with_minified_content()
    {
        //
    }
}
